<?php

namespace frontend\controllers;

use common\models\User;
use common\models\Campaign;
use common\models\Donation;
use common\components\PullrPayment;
/*
controller to view exported layout
 * donations
 *  */
class LayoutviewController extends \yii\web\Controller {

    public $campaign = null;
    
    public function getCampaign($userAlias, $campaignAlias){
        if (ctype_digit($userAlias)){
            $user = User::findOne($userAlias);
        } else {
            $user = User::findOne(['uniqueName' => $userAlias]);
        }
        
        if (!$user){
            throw new \yii\web\NotFoundHttpException("Such user don't exist");
        }
        
        $campaign = Campaign::findOne(['userId' => $user->id, 'status' => Campaign::STATUS_ACTIVE, 'alias' => $campaignAlias]);
        if (!$campaign){
            throw new \yii\web\NotFoundHttpException("Such campaign doesn't exist for user");
        }
        
        return $campaign;
    }
    public function actionView($userAlias, $campaignAlias) {
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        echo $this->renderPartial('index',['campaign' => $campaign]);
        die;
    }
    
    public function actionThankyou($userAlias, $campaignAlias){
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        echo $this->renderPartial('thankyou',['campaign' => $campaign]);
        die;
    }
    public function actionDonate($userAlias, $campaignAlias){
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
    
        /*passing campaign to layout*/
        $this->campaign = $campaign;
        $this->layout = 'donation';
        $donation = new Donation();
        $donation->createdDate = time();
        $donation->campaignId = $campaign->id;
        if (!\Yii::$app->user->isGuest){
            $donation->userId = \Yii::$app->user->id; 
        }
        if ($donation->load($_REQUEST) && $donation->save()){
            PullrPayment::donationPayment($donation);
        }
        
         if (isset($_REQUEST['paymentSuccess']) && ($_REQUEST['paymentSuccess'] == 'true')){
            $payment = new PullrPayment();
            $payment->completePayment();
            $this->redirect($userAlias.'/'.$campaignAlias.'/thankyou');
        }
        
        return $this->render('donate', [
            'campaign' => $campaign,
            'donation' => $donation
        ]);
    }
       
}
