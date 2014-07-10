<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Donation;
use common\models\Campaign;

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
        /*@var $user User */
        /*we are limiting by 100 here, but on html after appling campaing's filter we will limit to just 10*/
        $donations = $user->getDonations($since_id)->limit(100)->all();
        
        $donationsArray = [];
        foreach ($donations as $donation){
            /*@var $donation Donation*/
            $donationsArray[] = $donation->toArray(['id', 'campaignId', 'parentCampaignId', 'amount', 'nameFromForm', 'paymentDate']);
        }
        
        $data = [];
        $data['donations'] = $donationsArray;
        
        $stats = [
            'number_of_donations' => 0,
            'total_amount' => 0,
            'number_of_donors' => 0,
            'top_donation_amount' => 0,
            'top_donation_name' => 0,
            'top_donors' => [],
        ];
        $data['stats'] = $stats;
        echo json_encode($data);
        die;
    }
}
