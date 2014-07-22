<?php

namespace frontend\controllers;

use frontend\models\streamboard\StreamboardConfig;
use frontend\models\streamboard\StreamboardDonation;
use Yii;
use common\models\User;
use common\models\Donation;
use common\models\Campaign;
use frontend\models\streamboard\Streamboard;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class StreamboardController extends FrontendController{
    public function actionIndex() {
        $user = Yii::$app->user->identity;
        /**@var $user User */
        $campaigns = $user->getCampaigns()->all();

        $this->layout = 'streamboard';
        return $this->render('index', [
            'user' => $user,
            'campaigns' => $campaigns,
        ]);
    }

    public function actionSource() {
        $user = Yii::$app->user->identity;
        /**@var $user User */
        $campaigns = $user->getCampaigns()->all();

        $this->layout = 'streamboard/source';
        return $this->render('source', [
            'user' => $user,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * That is just development function to test new donations flow. 
     * @todo Should be removed at future. 
     */
    public function actionAdd_donation_ajax() {
        $campaignId = 1;
        $donation = new Donation();
        $donation->userId = \Yii::$app->user->id;;
        $donation->campaignId = $campaignId;
        $donation->amount =  rand(100, 10000);
        $donation->comments = 'test comments here'.rand(1,50);
        $donation->email = 'email'.rand(1,100).'@gmail.com';
        $donation->paymentDate = time();
        $donation->save();

        Campaign::updateDonationStatistics($campaignId);
    }

    public function actionGet_campaigns_ajax(){
        $user = \Yii::$app->user->identity;
        /**@var $user User */
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->with('streamboard')->orderBy('amountRaised DESC, id DESC')->all();
        $campaignsArray = [];

        foreach ($campaigns as $campaign){
            /**@var $campaign Campaign*/
            $array = $campaign->toArray(['id', 'name', 'goalAmount', 'amountRaised']);
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true: false;
            $campaignsArray[$campaign->id] = $array;
        }

        echo json_encode($campaignsArray);
        die;
    }

    public function actionGet_donations_ajax($since_id = null) {
        $user = \Yii::$app->user->identity;
        /**@var $user User */
        /*we are limiting by 100 here, but on html after applying campaign's filter we will limit to just 20*/
        $donations = $user->getDonations($since_id)->with('campaign', 'streamboard')->limit(100)->all();

        $donationsArray = [];
        foreach ($donations as $donation){
            /**@var $donation Donation*/
            $array = $donation->toArray(['id', 'campaignId', 'amount', 'nameFromForm', 'paymentDate', 'comments']);
            $array['campaignName'] = $donation->campaign->name;
            $array['streamboard'] = [
                'nameHidden' => false,
                'wasRead' => false
            ];
            if ($donation->streamboard){
                $array['streamboard']['nameHidden'] = $donation->streamboard->nameHidden;
                $array['streamboard']['wasRead'] = $donation->streamboard->wasRead;
            }
            $array['displayName'] = $donation->displayNameForDonation();
            $donationsArray[] = $array;
        }

        /**
         * we do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.
         */
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->with('streamboard')->all();
        $selectedCampaigns = [];

        foreach ($campaigns as $campaign){
            /**@var $campaign Campaign*/
            if ($campaign->streamboard->selected){
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

    public function actionSet_campaign_selection(){
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $streamboardSelected = $data['streamboardSelected'];
        $user = \Yii::$app->user->identity;
        /**@var $user User */

        $campaign = Campaign::findOne($id);

        if (!$campaign || $campaign->userId != $user->id){
            throw new ForbiddenHttpException();
        }

        $campaign->streamboard->selected = $streamboardSelected;
        $campaign->streamboard->save();
    }

    public function actionSet_donation_streamboard(){
        $data = json_decode(file_get_contents("php://input"), true);
        if (!in_array($data['property'],['nameHidden', 'wasRead'])){
           throw new NotFoundHttpException('Property not found');
        }
        $userId = \Yii::$app->user->id;
        $donation = Donation::findOne($data['id']);
        if (!(($donation->campaign->userId == $userId) || ($donation->campaign->parentCampaign->userId == $userId))){
           throw new ForbiddenHttpException();
        }
        StreamboardDonation::setForDonation($donation->id, $userId, $data['property'], $data['value']);
    }
    public function actionSet_streamboard_window(){
        $width = max(intval($_POST['width']),100);
        $height = max(intval($_POST['height']), 100);
        $config = StreamboardConfig::get();
        $config->streamboardWidth = $width;
        $config->streamboardHeight = $height;
        $config->save();
    }
    public function actionSet_streamboard_window_position(){
        $left = intval($_POST['left']);
        $top =intval($_POST['top']);
        $config = StreamboardConfig::get();
        $config->streamboardLeft = $left;
        $config->streamboardTop = $top;
        $config->save();
    }

    /**
     * @description - ajax request from app/streamboard/source page
     */
    public function actionGet_source_data(){
        $user = \Yii::$app->user->identity;
        /**
         * we do not include parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.
         */
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->all();
        $stats = Streamboard::getStats($campaigns);
        $data = [
            'stats' => $stats
        ];
        echo json_encode($data);
        die;
    }
}
