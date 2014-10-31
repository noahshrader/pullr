<?php

namespace frontend\controllers;

use common\models\Plan;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchSubscription;
use frontend\models\streamboard\StreamboardConfig;
use frontend\models\streamboard\StreamboardDonation;
use frontend\models\streamboard\StreamboardRegion;
use Yii;
use common\models\User;
use common\models\Donation;
use common\models\Campaign;
use frontend\models\streamboard\Streamboard;
use yii\base\ErrorException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\components\Application;
use yii\web\Response;
use common\components\streamboard\alert\AlertMediaManager;
use common\components\message\ActivityMessage;
use frontend\models\streamboard\WidgetDonationFeed;
use yii\filters\AccessControl;
class StreamboardController extends FrontendController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['source'],
                        'allow' => true
                    ],
                    [
                        'allow' => true,
                        'roles' => ['frontend'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'streamboard';
        $user = Application::getCurrentUser();

        /*to get 'donations/followers/subscribers/" only since opening of streamboard*/
        $user->streamboardConfig->streamRequestLastDate = time();
        $user->streamboardConfig->save();

        $regionsNumber = $user->getPlan() == Plan::PLAN_PRO ? 2 : 1;
        return $this->render('index', [
            'regionsNumber' => $regionsNumber
        ]);
    }

    /**return new events (donations/followers/subscribers)
     * sorting is "date ASC" for array
    */
    public function actionGet_stream_data(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Application::getCurrentUser();
        $streamboardConfig = $user->streamboardConfig;
        $time = time();
        /*we really query additional 5 seconds in case you open two streamboards or some other reason*/
        $sinceTime = $streamboardConfig->streamRequestLastDate - 5;

        $donations = $user->getDonations(['sincePaymentDate' => $sinceTime])->orderBy('paymentDate ASC, id ASC')->all();

        /*created date at Twitch is less for 1 hour then $sinceTime, as it possible we will have enough rare request to Twitch API,
        but notifications still should be shown, even if they are delayed*/

        $twitchSinceTime = $sinceTime - 60*60;
        $condition = 'createdAt > :twitchSinceTime and createdAtPullr > :sinceTime ';
        $params = ['twitchSinceTime' => $twitchSinceTime ,'sinceTime' => $sinceTime];

        $followers = TwitchFollow::find()->where(['userId' => $user->id])->andWhere($condition)->addParams($params)->all();
        $subscriptions = TwitchSubscription::find()->where(['userId' => $user->id])->andWhere($condition)->addParams($params)->all();

        $notifications = [];
        foreach ($donations as $donation){
            /**@var $donation Donation*/
            $notifications[] = [
                'id' => $donation->id,
                'type' => 'donations',
                'message' => ActivityMessage::messageDonationReceived($donation),
                'donation' => $donation,
                'date' => $donation->paymentDate
            ];
        }

        foreach ($followers as $follow){
            /**@var $follow TwitchFollow*/
            $notifications[] = [
                'id' => $follow->twitchUserId,
                'type' => 'followers',
                'message' => ActivityMessage::messageNewTwitchFollower($follow),
                'follow' => $follow,
                'date' => $follow->createdAt
            ];
        }

        foreach ($subscriptions as $subscription){
            /**@var $subscription TwitchSubscription*/
            $notifications[] = [
                'id' => $subscription->twitchUserId,
                'type' => 'subscribers',
                'message' => ActivityMessage::messageNewTwitchSubscriber($subscription),
                'subscription' => $subscription,
                'date' => $subscription->createdAtPullr
            ];
        }

        usort($notifications, function($a, $b){
            return $a['date'] > $b['date'] ? 1 : -1;
        });

        $streamboardConfig->streamRequestLastDate = $time;
        return $notifications;
    }

    public function actionSource($userId = null)
    {
        $hideAngularJsPage = false;

        


        if ($userId != null) {
            $user = User::findOne($userId);            
            if ($user == null) {
                throw new ForbiddenHttpException();
            }
            $hideAngularJsPage = true;
        } else {
            $user = Application::getCurrentUser();        
        }
        
        $this->layout = 'streamboard/source';
        $data = $this->getSourceData($user);
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$user->id])->one();
        if ( $donationFeed ) {
            $donationFeedSetting = $donationFeed->toArray(['showSubscriber', 'showFollower']);
            $showSubscriber = $donationFeedSetting['showSubscriber'];
            $showFollower = $donationFeedSetting['showFollower'];
        } else {
            $showSubscriber = false;
            $showFollower = false;
        }
        $data['showSubscriber'] = $showSubscriber;
        $data['showFollower'] = $showFollower;
        
        $data['campaigns'] = $this->getUserCampaigns($user);
        $data['twitchPartner'] = $user->userFields->twitchPartner;        
        
        return $this->render('config/settings/source', [
            'data' => $data,
            'hideAngularJsPage' => $hideAngularJsPage
        ]);
    }

    public function actionGet_campaigns_ajax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        return $this->getUserCampaigns($user);
    }

    public function getUserCampaigns(User $user)
    {        
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->orderBy('amountRaised DESC, id DESC')->all();
        $campaignsArray = [];

        foreach ($campaigns as $campaign) {            
            /**@var $campaign Campaign */
            $array = $campaign->toArray(['id', 'name', 'goalAmount', 'amountRaised', 'numberOfDonations', 'numberOfUniqueDonors', 'userId']);        
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true : false;
            $campaignsArray[$campaign->id] = $array;
        }
        
        return $campaignsArray;
    }

    public function actionGet_donations_ajax($since_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        $user = Application::getCurrentUser();
        $sinceDate = $user->streamboardConfig->clearedDate;
        /*we are limiting by 100 here, but on html after applying campaign's filter we will limit to just 20*/
        $donations = $user->getDonations(['sinceId' => $since_id])
                            ->andWhere('paymentDate > ' . $sinceDate)
                            ->with('campaign', 'streamboard')
                            ->orderBy('amount DESC')
                            ->limit(100)                            
                            ->all();

        $donationsArray = [];
        foreach ($donations as $donation) {
            /**@var $donation Donation */
            $array = $donation->toArray(['id', 'campaignId', 'amount', 'nameFromForm', 'paymentDate', 'comments']);
            $array['campaignName'] = $donation->campaign->name;
            $array['streamboard'] = [
                'nameHidden' => false,
                'wasRead' => false
            ];
            if ($donation->streamboard) {
                $array['streamboard']['nameHidden'] = $donation->streamboard->nameHidden;
                $array['streamboard']['wasRead'] = $donation->streamboard->wasRead;
            }
            $array['displayName'] = $donation->displayNameForDonation();
            $donationsArray[] = $array;
        }

        $subscribers = [];
        if ($user->userFields->twitchPartner) {
            $subscriptions = TwitchSubscription::find()->where(['userId' => $user->id])->andWhere('createdAt > ' . $sinceDate)->orderBy('createdAt DESC')->all();
            foreach ($subscriptions as $subscription) {
                /** @var TwitchSubscription $subscription */
                $subscribers[] = $subscription->toArray(['twitchUserId', 'display_name', 'createdAt']);
            }
        }

        $followers = [];

        if ($user->userFields->twitchPartner) {
            $twitchFollowers = TwitchFollow::find()->where(['userId' => $user->id])->andWhere('createdAt > ' . $sinceDate)->orderBy('createdAt DESC')->all();
            foreach ($twitchFollowers as $twitchFollower) {
                /** @var TwitchSubscription $subscription */
                $followers[] = $twitchFollower->toArray(['twitchUserId', 'display_name', 'createdAt']);
            }
        }

        /*We do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.*/
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->all();
        $selectedCampaigns = [];

        foreach ($campaigns as $campaign) {
            /**@var $campaign Campaign */
            if ($campaign->streamboard->selected) {
                $selectedCampaigns[] = $campaign;
            }
        }

        $stats = Streamboard::getStats($selectedCampaigns);


        $data = [];
        $data['donations'] = $donationsArray;
        $data['stats'] = $stats;
        $data['followers'] = $followers;
        $data['subscribers'] = $subscribers;
        return $data;
    }

    public function actionSet_campaign_selection()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $streamboardSelected = $data['streamboardSelected'];
        $user = \Yii::$app->user->identity;
        /**@var $user User */

        $campaign = Campaign::findOne($id);

        if (!$campaign || $campaign->userId != $user->id) {
            throw new ForbiddenHttpException();
        }

        $campaign->streamboard->selected = $streamboardSelected;
        $campaign->streamboard->save();
    }

    public function actionSet_donation_streamboard()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!in_array($data['property'], ['nameHidden', 'wasRead'])) {
            throw new NotFoundHttpException('Property not found');
        }
        $userId = \Yii::$app->user->id;
        $donation = Donation::findOne($data['id']);
        if (!(($donation->campaign->userId == $userId) || ($donation->campaign->parentCampaign->userId == $userId))) {
            throw new ForbiddenHttpException();
        }
        StreamboardDonation::setForDonation($donation->id, $userId, $data['property'], $data['value']);
    }

    public function actionSet_activity_feed_setting()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if( ! (isset($data['showSubscriber']) || isset($data['showFollower']))) {
            throw new ForbiddenHttpException();
        }
        $userId = \Yii::$app->user->id;
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$userId])->one();
        if ( ! $donationFeed ) {
            throw new ForbiddenHttpException();
        }

        if (isset($data['showSubscriber'])) {
            $donationFeed->showSubscriber = $data['showSubscriber'];            
        }

        if (isset($data['showFollower'])) {
            $donationFeed->showFollower = $data['showFollower'];
        }

        $donationFeed->save();
    }

    public function actionGet_activity_feed_setting() 
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = \Yii::$app->user->id;
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$userId])->one();
        if ( ! $donationFeed ) {
            throw new ForbiddenHttpException();
        }
        return $donationFeed->toArray(['showSubscriber', 'showFollower']);
    }

    public function actionSet_streamboard_window()
    {
        $width = max(intval($_POST['width']), 100);
        $height = max(intval($_POST['height']), 100);
        $config = StreamboardConfig::get();
        $config->streamboardWidth = $width;
        $config->streamboardHeight = $height;
        $config->save();
    }

    public function actionSet_streamboard_window_position()
    {
        $left = intval($_POST['left']);
        $top = intval($_POST['top']);
        $config = StreamboardConfig::get();
        $config->streamboardLeft = $left;
        $config->streamboardTop = $top;
        $config->save();
    }

    /**
     * @description - ajax request from app/streamboard/source page
     */
    public function actionGet_source_data()
    {
        $user = Application::getCurrentUser();
        $data = $this->getSourceData($user);
        echo json_encode($data);
    }

    public function getSourceData(User $user)
    {        
        /*We do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.*/
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->all();
        $stats = Streamboard::getStats($campaigns);

        $twitchUser = $user->twitchUser;
        $twitchUserArray = $twitchUser ? $twitchUser->toArray(['followersNumber', 'subscribersNumber']) : null;

        $sinceDate = $user->streamboardConfig->clearedDate;
        $selectedCampaigns = Streamboard::getSelectedCampaigns($user);
        $donors = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate);

        $subscribers = [];
        if ($user->userFields->twitchPartner) {
            $subscriptions = TwitchSubscription::find()->where(['userId' => $user->id])->andWhere('createdAt > ' . $sinceDate)->orderBy('createdAt DESC')->all();
            foreach ($subscriptions as $subscription) {
                /** @var TwitchSubscription $subscription */
                $subscribers[] = $subscription->toArray(['twitchUserId', 'display_name', 'createdAt']);
            }
        }

        $followers = [];
        if ($user->userFields->twitchPartner) {
            $twitchFollowers = TwitchFollow::find()->where(['userId' => $user->id])->andWhere('createdAt > ' . $sinceDate)->orderBy('createdAt DESC')->all();
            foreach ($twitchFollowers as $twitchFollower) {
                /** @var TwitchSubscription $subscription */
                $followers[] = $twitchFollower->toArray(['twitchUserId', 'display_name', 'createdAt']);
            }
        }
            
        $data = [
            'stats' => $stats,
            'twitchUser' => $twitchUserArray,
            'donors' => $donors,
            'subscribers' => $subscribers,
            'followers' => $followers,
            'followersNumber' => TwitchFollow::getFollowerCountByTotal($user->id),
            'subscribersNumber' => TwitchSubscription::getSubscriberCountByTotal($user->id),
            'emptyActivityMessage' => $user->getEmptyActivityFeedMessage()
        ];
        return $data;
    }

    public function actionClear_button_ajax()
    {
        if (!\Yii::$app->request->isPost) {
            return;
        }

        $user = Application::getCurrentUser();
        $user->streamboardConfig->clearedDate = time();
        $user->streamboardConfig->save();
    }

    public function actionGet_regions_ajax()
    {
        $user = Application::getCurrentUser();

        $regions = StreamboardRegion::GetRegions($user);

        $data = [];
        foreach ($regions as $region) {
            $data[] = $region->toArray();
        }

        echo json_encode($data);
    }

    public function actionUpdate_region_ajax()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = Application::getCurrentUser();
        if ($user->id != $data['userId']) {
            throw new ForbiddenHttpException();
        }
        $region = StreamboardRegion::findOne(['userId' => $data['userId'], 'regionNumber' => $data['regionNumber']]);
        /** @var $region StreamboardRegion */

        if ($region->updateFromArray($data, '')) {
            $response = ['code' => 'success', 'region' => $region->toArray()];
            echo json_encode($response);
        } else {
            echo 'error';
            var_dump($region->getErrors());
        }
    }

    public function actionUpload_alert_file_ajax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $type = Yii::$app->request->post('type');
        $user = Application::getCurrentUser();
        if (!$user) {
            throw new ForbiddenHttpException();
        }
        switch ($type) {
            case 'sound':
                $result = AlertMediaManager::uploadSound();
                break;
            case 'image':
                $result = AlertMediaManager::uploadImage();
                break;
            case 'campaignBackground':
                $result = AlertMediaManager::uploadCampaignBackgrounds();
                break;
            default:
                throw new ErrorException('Upload type should be either "image" or "sound" or "campaignBackground" or "campaignAlertBackground"');
        }
        if (!$result){
            throw new ErrorException('Error during upload');
        }
        $manager = new AlertMediaManager();
        switch ($type){
            case 'sound':
                return $manager->getCustomSounds();
            case 'image':
                return $manager->getCustomImages();
            case 'campaignBackground':
                return $manager->getCustomCampaignBackgrounds();     
        }
    }

    private function removeAlert_file_ajax($type){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        if (!$user) {
            throw new ForbiddenHttpException();
        }
        $fileName = file_get_contents("php://input");

        $manager = new AlertMediaManager();
        switch ($type){
            case 'sound' :
                $result = AlertMediaManager::removeSound($fileName);
                $files = $manager->getCustomSounds();
                break;
            case 'campaignBackground':
                $result = AlertMediaManager::removeCampaignBackground($fileName);
                $files = $manager->getCustomCampaignBackgrounds();
                break;
            case 'image' :
                $result = AlertMediaManager::removeImage($fileName);
                $files = $manager->getCustomImages();
                break;
        }

        if ($result){
            return $files;
        } else {
            throw new ErrorException('Cannot remove file');
        }
    }
    
    public function actionAlert_remove_campaign_background_ajax(){
        return $this->removeAlert_file_ajax('campaignBackground');
    }

    public function actionAlert_remove_sound_ajax(){
       return $this->removeAlert_file_ajax('sound');
    }

    public function actionAlert_remove_image_ajax(){
        return $this->removeAlert_file_ajax('image');
    }
}
