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
use common\models\CampaignInvite;
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
use frontend\models\streamboard\StreamboardTestAlert;

class StreamboardController extends FrontendController
{
    protected $user;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['source', 'region', 'get_followers', 'get_regions_ajax', 'get_stream_data',
                                    'get_donations_ajax', 'get_subscribers', 'get_streamboard_config', 'get_activity_feed_setting',
                                    'get_source_data', 'get_campaigns_ajax', 'test_ajax', 'update_region_ajax'],
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

    public function init() {
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, streamboardToken');

        if (\Yii::$app->request->isOptions)
        {
            echo 'Invalid request';
            \Yii::$app->end();
        }

        return parent::init();
    }

    public function getPayload()
    {
        $payload = file_get_contents("php://input");
        $payload = json_decode($payload, true);
        return $payload;
    }

    public function actionIndex()
    {
        $this->layout = 'streamboard';
        $user = Streamboard::getCurrentUser();

        /*to get 'donations/followers/subscribers/" only since opening of streamboard*/
        $user->streamboardConfig->streamRequestLastDate = time();
        $user->streamboardConfig->save();

        $regionsNumber = $user->getPlan() == Plan::PLAN_PRO ? 2 : 1;
        $regionsData = $this->getRegionsData($user);
        $donationsData = $this->getDonationsData($user);
        $campaignsData = $this->getUserCampaigns($user);
        return $this->render('index', [
            'regionsNumber' => $regionsNumber,
            'regionsData' => $regionsData,
            'donationsData' => $donationsData,
            'campaignsData' => $campaignsData,
            'streamboardConfig' => $user->streamboardConfig->toArray()
        ]);
    }

    public function actionRegion($streamboardToken, $regionNumber, $bg = false) {
        $headers = \Yii::$app->request->getHeaders()->toArray();
        $this->layout = 'streamboard/region';

        $user = Streamboard::getCurrentUser();
        if (!$user) {
            throw new ForbiddenHttpException();
        }

        $user->streamboardConfig->streamRequestLastDate = time();
        $user->streamboardConfig->save();

        $regions = StreamboardRegion::GetRegions($user);
        $region = isset($regions[$regionNumber - 1]) ? $regions[$regionNumber - 1] : [];
        $region = $region->toArray();
        $regionsData = $this->getRegionsData($user);
        $donationsData = $this->getDonationsData($user);
        $campaignsData = $this->getUserCampaigns($user);
        return $this->render('region', [
            'regionNumber' => $regionNumber,
            'streamboardToken' => $streamboardToken,
            'region' => $region,
            'showBackground' => $bg,
            'regionsData' => $regionsData,
            'donationsData' => $donationsData,
            'campaignsData' => $campaignsData,
            'streamboardConfig' => $user->streamboardConfig->toArray()
        ]);
    }

    /**return new events (donations/followers/subscribers)
     * sorting is "date ASC" for array
    */
    public function actionGet_stream_data() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Streamboard::getCurrentUser();
        $streamboardConfig = $user->streamboardConfig;
        $time = $streamboardConfig->streamRequestLastDate;

        $sinceTime = $time - 60;

        $donations = $user->getDonations(['sinceCreateDate' => $sinceTime])->orderBy('paymentDate ASC, id ASC')->all();


        $twitchSinceTime = $sinceTime - 60*60;
        $condition = 'createdAt > :twitchSinceTime and createdAtPullr > :sinceTime ';
        $params = ['twitchSinceTime' => $twitchSinceTime ,'sinceTime' => $sinceTime];

        $followers = TwitchFollow::find()->where(['userId' => $user->id])->andWhere($condition)->addParams($params)->all();
        $subscriptions = TwitchSubscription::find()->where(['userId' => $user->id])->andWhere($condition)->addParams($params)->all();

        $notifications = [];
        foreach ($donations as $donation) {
            /**@var $donation Donation*/
            $notifications[] = [
                'id' => $donation->id,
                'type' => 'donations',
                'message' => ActivityMessage::messageDonationReceived($donation),
                'donation' => $donation,
                'date' => $donation->paymentDate
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
            $user = Streamboard::getCurrentUser();
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
        if ($user->userFields->twitchChannel) {
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
        $user = Streamboard::getCurrentUser();
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

            $array = $campaign->toArray(['id', 'name', 'goalAmount', 'amountRaised', 'numberOfDonations', 'numberOfUniqueDonors', 'userId', 'type']);
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true : false;
            $campaignsArray[$campaign->id] = $array;
        }

        return $campaignsArray;
    }

    public function actionGet_streamboard_config()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
        $streamboardConfig = $user->streamboardConfig;
        return $streamboardConfig->toArray();
    }

    public function actionGet_donations_ajax($since_id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
        $data = $this->getDonationsData($user, $since_id);
        return $data;
    }

    public function getDonationsData($user, $since_id = null) {

//        $selectedCampaigns = Streamboard::getSelectedCampaigns($user);

        $sinceDate = $user->streamboardConfig->clearedDate;

        $selectedCampaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, true)->orderBy('amountRaised DESC, id DESC')->all();

        $donations = [];
        $donationsByName = [];
        $donationsByEmail = [];

        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$user->id])->one();
        if ( $donationFeed && $donationFeed->groupUser == 1 ) {
            $groupUser = true;
        } else {
            $groupUser = false;
        }

//        $donations = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate);
        $donationsByName = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate,'name');
        $donationsByEmail = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate,'email');

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

        if ($user->userFields->twitchChannel) {
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

        $data['donationsByEmail'] = $donationsByEmail;
        $data['donationsByName'] = $donationsByName;

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
        $countInvite = CampaignInvite::find()->where(['userId' => $user->id, 'campaignId' => $id, 'status' => CampaignInvite::STATUS_ACTIVE ])->count();
        $campaign = Campaign::findOne($id);

        if ((!$campaign && $countInvite == 0) && ($campaign && $campaign->userId != $user->id)) {
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

        if( ! (isset($data['showSubscriber']) || isset($data['showFollower']) || isset($data['groupUser']) || isset($data['groupBase']) || isset($data['noDonationMessage']))) {
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

        if (isset($data['groupBase'])) {
            $donationFeed->groupBase = $data['groupBase'];
        }

        if (isset($data['noDonationMessage'])) {
            $donationFeed->noDonationMessage = $data['noDonationMessage'];
        }

        $donationFeed->save();
    }

    public function actionGet_activity_feed_setting()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
        $donationFeed = WidgetDonationFeed::find()->where(['userId'=>$user->id])->one();
        if ( ! $donationFeed ) {
            throw new ForbiddenHttpException();
        }
        return $donationFeed->toArray(['showSubscriber', 'showFollower', 'groupUser', 'groupBase', 'noDonationMessage']);
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
        $user = Streamboard::getCurrentUser();
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

        $user = Streamboard::getCurrentUser();
        $user->streamboardConfig->clearedDate = time();
        $user->streamboardConfig->save();
    }

    public function actionGet_regions_ajax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
        $data = $this->getRegionsData($user);
        return $data;
    }

    public function getRegionsData($user)
    {
        $regions = StreamboardRegion::GetRegions($user);
        $data = [];
        foreach ($regions as $region) {
            $data[] = $region->toArray();
        }
        return $data;
    }

    public function actionUpdate_region_ajax()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = Streamboard::getCurrentUser();
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
        $user = Streamboard::getCurrentUser();
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
        $user = Streamboard::getCurrentUser();
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

    public function actionGet_subscribers() {

        if ( ! Yii::$app->request->isPost) {
            Yii::$app->end();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
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

        if ( ! Yii::$app->request->isPost) {
            Yii::$app->end();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Streamboard::getCurrentUser();
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

    public function actionSet_enable_featured_campaign()
    {
        $payload = $this->getPayload();
        if (isset($payload['enableFeaturedCampaign'])) {
            $user = Streamboard::getCurrentUser();
            $streamboardConfig = $user->streamboardConfig;
            $streamboardConfig->enableFeaturedCampaign = (bool)$payload['enableFeaturedCampaign'];
            $streamboardConfig->save();
        }
    }

    public function actionSet_featured_campaign_id()
    {
        $payload = $this->getPayload();
        if (isset($payload['featuredCampaignId'])) {
            $user = Streamboard::getCurrentUser();
            $streamboardConfig = $user->streamboardConfig;
            $streamboardConfig->featuredCampaignId = intval($payload['featuredCampaignId']);
            $streamboardConfig->save();
        }
    }

    public function actionGet_test_alert()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Streamboard::getCurrentUser();
        $streamboardConfig = $user->streamboardConfig;
        $testAlerts = StreamboardTestAlert::find()
                        ->where('createdAt > :streamRequestLastDate')
                        ->andWhere(['userId' => $user->id])
                        ->addParams([
                            'streamRequestLastDate' => $streamboardConfig->streamRequestLastDate
                        ])
                        ->all();
        $result = [];
        foreach ($testAlerts as $testAlert) {
            $result[] = $testAlert->toArray(['id','alertType','regionNumber','createdAt']);
        }
        return $result;
    }

    public function actionAdd_test_alert()
    {
        $post = $this->getPayload();
        if (isset($post['alertType']) && isset($post['regionNumber'])) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $user = Streamboard::getCurrentUser();
            $testAlert = new StreamboardTestAlert();
            $testAlert->userId = $user->id;
            $testAlert->alertType = $post['alertType'];
            $testAlert->regionNumber = $post['regionNumber'];
            $testAlert->createdAt = time();
            return $testAlert->save();
        }

    }
}
