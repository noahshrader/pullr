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
use common\components\PullrUtils;
use yii\web\Response;
use common\components\streamboard\alert\AlertMediaManager;
use common\components\message\ActivityMessage;
use frontend\models\streamboard\WidgetDonationFeed;
use yii\filters\AccessControl;
use common\models\twitch\TwitchUser;
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

        foreach ($followers as $follow) {
            /**@var $follow TwitchFollow*/
            $notifications[] = [
                'id' => $follow->twitchUserId,
                'type' => 'followers',
                'message' => ActivityMessage::messageNewTwitchFollower($follow->display_name),
                'follow' => $follow,
                'date' => $follow->createdAt
            ];
        }

        foreach ($subscriptions as $subscription) {
            /**@var $subscription TwitchSubscription*/
            $notifications[] = [
                'id' => $subscription->twitchUserId,
                'type' => 'subscribers',
                'message' => ActivityMessage::messageNewTwitchSubscriber($subscription->display_name),
                'subscription' => $subscription,
                'date' => $subscription->createdAt
            ];
        }

        usort($notifications, function($a, $b){
            return $a['date'] > $b['date'] ? 1 : -1;
        });

        $streamboardConfig->streamRequestLastDate = $time;
        return $notifications;
    }

    public function actionSource($twitchUsername = null, $userId = null)
    {
        $hideAngularJsPage = false;

        if ($twitchUsername != null) {
            $user = User::findByTwitchUsername($twitchUsername);            
            if ($user === false) {
                throw new ForbiddenHttpException();
            }
            $hideAngularJsPage = true;
        } else if ($userId != null) {
            $user = User::find($userId)->one();     
        } else {
            $user = Application::getCurrentUser();
        }
        
        $this->layout = 'streamboard/source';
        $data = $this->getSourceData($user);
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$user->id])->one();
        if ( $donationFeed ) {
            $donationFeedSetting = $donationFeed->toArray(['showSubscriber', 'showFollower', 'groupUser']);
            $showSubscriber = $donationFeedSetting['showSubscriber'];
            $showFollower = $donationFeedSetting['showFollower'];
            $groupUser = $donationFeedSetting['groupUser'];
        } else {
            $showSubscriber = false;
            $showFollower = false;
            $groupUser = false;
        }

        $sinceDate = $user->streamboardConfig->clearedDate;
        $selectedCampaigns = Streamboard::getSelectedCampaigns($user);
        $donors = [];
        $groupDonors = [];
        if ($groupUser) {
            $groupDonors = Donation::getTopDonorsForCampaignsGroupByAmount($selectedCampaigns, null, true, $sinceDate);
        } else {
            $donors = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate);
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

        $data['showSubscriber'] = $showSubscriber;
        $data['showFollower'] = $showFollower;
        $data['donors'] = $donors;
        $data['subscribers'] = $subscribers;
        $data['followers'] = $followers;
        $data['groupUser'] = $groupUser;
        $data['campaigns'] = $this->getUserCampaigns($user);
        $data['twitchPartner'] = $user->userFields->twitchPartner;        
        $data['groupDonors'] = $groupDonors;
        
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
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, true)->orderBy('amountRaised DESC, id DESC')->all();
        $campaignsArray = [];

        foreach ($campaigns as $campaign) {            
            //number format
            $campaign->goalAmount = PullrUtils::formatNumber($campaign->goalAmount, 2);
            $campaign->amountRaised = PullrUtils::formatNumber($campaign->amountRaised, 2);
            $campaign->numberOfDonations = PullrUtils::formatNumber($campaign->numberOfDonations, 2);
            /**@var $campaign Campaign */
            
            $array = $campaign->toArray(['id', 'name', 'goalAmount', 'amountRaised', 'numberOfDonations', 'numberOfUniqueDonors', 'userId']);        
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true : false;
            $campaignsArray[$campaign->id] = $array;
        }
        
        return $campaignsArray;
    }

    public function actionGet_streamboard_config()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        $streamboardConfig = $user->streamboardConfig;
        return $streamboardConfig->toArray();
    }   

    public function actionGet_donations_ajax($since_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();        
        $sinceDate = $user->streamboardConfig->clearedDate;        
        $selectedCampaigns = Streamboard::getSelectedCampaigns($user);        
        $donations = [];
      
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$user->id])->one();
        if ( $donationFeed && $donationFeed->groupUser == 1 ) {           
            $groupUser = true;
        } else {           
            $groupUser = false;
        }        
      
        $donations = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate);        

        $userDonations = $user->getDonations(['sinceId' => $since_id])
                            ->andWhere('paymentDate > ' . $sinceDate)
                            ->with('campaign', 'streamboard')                      
                            ->limit(100)                            
                            ->all();
        $userDonationArray = [];
        foreach ($userDonations as $donation) {
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
            $userDonationArray[] = $array;
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
        $data['donations'] = $donations;        
        $data['stats'] = $stats;
        $data['followers'] = $followers;
        $data['subscribers'] = $subscribers;
        $data['userDonations'] = $userDonationArray;
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

        if( ! (isset($data['showSubscriber']) || isset($data['showFollower']) || isset($data['groupUser']))) {
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

        if (isset($data['groupUser'])) {
            $donationFeed->groupUser = $data['groupUser'];
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
        return $donationFeed->toArray(['showSubscriber', 'showFollower', 'groupUser']);
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

    public function actionSet_streamboard_sidepanel_width() {
        $data = json_decode(file_get_contents("php://input"), true);
        $width = intval($data['width']);
        $config = StreamboardConfig::get();
        $config->sidePanelWidth = $width;
        $config->save();
    }

    public function actionSet_streamboard_region2_height() {
        $data = json_decode(file_get_contents("php://input"), true);
        $height = intval($data['height']);
        $config = StreamboardConfig::get();
        $config->region2HeightPercent = $height;
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
        
        $stats['total_amountRaised'] = PullrUtils::formatNumber($stats['total_amountRaised'], 2);
        $stats['total_goalAmount'] = PullrUtils::formatNumber($stats['total_goalAmount'], 2);
        
        if(!empty($stats['top_donation'])) {
            $stats['top_donation']['amount'] = PullrUtils::formatNumber($stats['top_donation']['amount'], 2);
        }
        
        $twitchUser = $user->twitchUser;
        $twitchUserArray = $twitchUser ? $twitchUser->toArray(['followersNumber', 'subscribersNumber']) : null;

        
            
        $data = [
            'stats' => $stats,
            'twitchUser' => $twitchUserArray,         
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

    public function actionGet_follower() {
        $channel = $user->userFields->twitchChannel;
        
        if ( ! $channel) {
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->channelFollows($channel, 1);
        $data = json_decode(json_encode($data), true);
    }
    public function actionGet_subscribers() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        $accessToken = $user->userFields->twitchAccessToken;
        $channel = $user->userFields->twitchChannel;
        $twitchPartner = $user->userFields->twitchPartner;

        if ( ! $accessToken || ! $channel || ! $twitchPartner) {
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->authChannelSubscriptions($accessToken, $channel, 100);
        /*to have array instead of object */
        $data = json_decode(json_encode($data), true);
        TwitchUser::updateSubscribersNumber($user, $data['_total']);   
        TwitchSubscription::updateSubscriptions($user, $data['subscriptions']);
        return $data;
    }

    public function actionGet_followers() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Application::getCurrentUser();
        $channel = $user->userFields->twitchChannel;
        
        if ( ! $channel) {
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->channelFollows($channel, 100);               
        $data = json_decode(json_encode($data), true);
        TwitchUser::updateFollowersNumber($user, $data['_total']);   
        TwitchFollow::updateFollows($user, $data['follows']);
        return $data;
    }
}
