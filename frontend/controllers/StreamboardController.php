<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Donation;
use common\models\Campaign;
use frontend\models\streamboard\StreamboardCampaign;
use yii\web\ForbiddenHttpException;

class StreamboardController extends FrontendController{
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;
        
        /*@var $user User */
        $campaigns = $user->getCampaigns()->all();
        
        $this->layout = 'streamboard';
        return $this->render('index', [
            'user' => $user,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * That is just development function to test new donations flow. 
     * @todo Should be removed at future. 
     */
    public function actionAdd_donation_ajax() {
        $donation = new Donation();
        $donation->userId = \Yii::$app->user->id;;
        $donation->campaignId = 1;
        $donation->amount =  rand(100, 10000);
        $donation->nameFromForm = 'rand_user_'.intval(rand(1,3));
        $donation->comments = 'test comments here'.rand(1,50);
        $donation->paymentDate = time();
        $donation->save();
    }

    public function actionGet_donations_ajax($since_id = null) {
        $user = \Yii::$app->user->identity;
        /**@var $user User */
        /*we are limiting by 100 here, but on html after appling campaing's filter we will limit to just 10*/
        $donations = $user->getDonations($since_id)->limit(100)->all();

        $donationsArray = [];
        foreach ($donations as $donation){
            /*@var $donation Donation*/
            $array = $donation->toArray(['id', 'campaignId', 'amount', 'nameFromForm', 'paymentDate', 'comments']);
            $array['campaignName'] = $donation->campaign->name;

            $donationsArray[] = $array;
        }

        /**
         * do not inlcude parents campaigns. If we will include it, we should prepare StreamboardCampaigns for such users.
         */
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->with('streamboard')->all();
        $campaignsArray = [];
        foreach ($campaigns as $campaign){
            /*@var $campaign Campaign*/
            $array = $campaign->toArray(['id', 'name']);
            $array['streamboardSelected'] = $campaign->streamboard->selected ? true: false;
            $campaignsArray[$campaign->id] = $array;
        }

        $stats = [
            'number_of_donations' => 0,
            'total_amount' => 0,
            'number_of_donors' => 0,
            'top_donation_amount' => 0,
            'top_donation_name' => 0,
            'top_donors' => [],
        ];
        
        $data = [];
        $data['donations'] = $donationsArray;
        $data['campaigns'] = $campaignsArray;
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
}
