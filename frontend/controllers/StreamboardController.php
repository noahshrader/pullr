<?php

namespace frontend\controllers;

use common\models\Plan;
use common\models\twitch\TwitchSubscription;
use frontend\models\streamboard\StreamboardConfig;
use frontend\models\streamboard\StreamboardDonation;
use frontend\models\streamboard\StreamboardRegion;
use Yii;
use common\models\User;
use common\models\Donation;
use common\models\Campaign;
use frontend\models\streamboard\Streamboard;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\components\Application;

class StreamboardController extends FrontendController
{
    public function actionIndex()
    {
        $this->layout = 'streamboard';
        $user = Application::getCurrentUser();
        $regionsNumber = $user->getPlan() == Plan::PLAN_PRO ? 2 : 1;
        return $this->render('index', [
            'regionsNumber' => $regionsNumber
        ]);
    }

    public function actionSource()
    {
        $this->layout = 'streamboard/source';
        return $this->render('source', [
        ]);
    }

    /**
     * That is just development function to test new donations flow.
     * @todo Should be removed at future.
     */
    public function actionAdd_donation_ajax()
    {
        $campaignId = 1;
        $donation = new Donation();
        $donation->userId = \Yii::$app->user->id;;
        $donation->campaignId = $campaignId;
        $donation->amount = rand(100, 10000);
        $donation->comments = 'test comments here' . rand(1, 50);
        $donation->email = 'email' . rand(1, 100) . '@gmail.com';
        $donation->paymentDate = time();
        $donation->save();

        Campaign::updateDonationStatistics($campaignId);
    }

    public function actionGet_campaigns_ajax()
    {
        $user = Application::getCurrentUser();
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->with('streamboard')->orderBy('amountRaised DESC, id DESC')->all();
        $campaignsArray = [];

        foreach ($campaigns as $campaign) {
            /**@var $campaign Campaign */
            $array = $campaign->toArray(['id', 'name', 'goalAmount', 'amountRaised', 'numberOfDonations', 'numberOfUniqueDonors']);
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true : false;
            $campaignsArray[$campaign->id] = $array;
        }

        echo json_encode($campaignsArray);
        die;
    }

    public function actionGet_donations_ajax($since_id = null)
    {
        $user = Application::getCurrentUser();
        $sinceDate = $user->streamboardConfig->clearedDate;
        /*we are limiting by 100 here, but on html after applying campaign's filter we will limit to just 20*/
        $donations = $user->getDonations($since_id)->andWhere('paymentDate > '.$sinceDate)->with('campaign', 'streamboard')->limit(100)->all();

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

        /*We do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.*/
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->with('streamboard')->all();
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

        echo json_encode($data);
        die;
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
        /*We do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.*/
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->all();
        $stats = Streamboard::getStats($campaigns);

        $twitchUser = $user->twitchUser;
        $twitchUserArray = $twitchUser ? $twitchUser->toArray(['followersNumber', 'subscribersNumber']) : null;

        $sinceDate = $user->streamboardConfig->clearedDate;
        $selectedCampaigns = Streamboard::getSelectedCampaigns();
        $donors = Donation::getTopDonorsForCampaigns($selectedCampaigns, null, true, $sinceDate);

        $subscribers = [];
        if ($user->userFields->twitchPartner){
            $subscriptions = TwitchSubscription::find()->where(['userId' => $user->id])->andWhere('createdAt > '. $sinceDate)->orderBy('createdAt DESC')->all();
            foreach ($subscriptions as $subscription){
                /** @var TwitchSubscription $subscription*/
                $subscribers[] = $subscription->toArray(['twitchUserId', 'display_name', 'createdAt']);
            }
        }
        $data = [
            'stats' => $stats,
            'twitchUser' => $twitchUserArray,
            'donors' => $donors,
            'subscribers' => $subscribers
        ];
        echo json_encode($data);
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

    public function actionGet_regions_ajax(){
       $user = Application::getCurrentUser();

       $regions = StreamboardRegion::GetRegions($user);

       $data = [];
       foreach ($regions as $region){
           $data[] = $region->toArray();
       }

       echo json_encode($data);
    }

    public function actionUpdate_region_ajax()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = Application::getCurrentUser();
        if ($user->id != $data['userId']){
            throw new ForbiddenHttpException();
        }
        $region = StreamboardRegion::findOne(['userId' => $data['userId'], 'regionNumber' => $data['regionNumber']]);
        /** @var $region StreamboardRegion */

        if ($region->updateFromArray($data, '')){
            $response = ['code' => 'success', 'region' => $region->toArray()];
            echo json_encode($response);
        }
        else {
            echo 'error';
            var_dump($region->getErrors());
        }
    }
}
