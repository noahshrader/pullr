<?php

namespace frontend\controllers;

use common\models\Theme;
use common\models\User;
use common\models\Campaign;
use common\models\Donation;
use common\components\PullrPayment;
use common\components\FirstGivingPayment;
use yii\web\Request;

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
            throw new \yii\web\NotFoundHttpException("Such user doesn't exist");
        }
        
        $campaign = Campaign::findOne(['userId' => $user->id, 'status' => Campaign::STATUS_ACTIVE, 'alias' => $campaignAlias]);
        if (!$campaign){
            throw new \yii\web\NotFoundHttpException("Such campaign doesn't exist for user");
        }
        
        return $campaign;
    }

    public function actionView($userAlias, $campaignAlias) {
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        $campaignTheme = $campaign->getTheme()->one();

        if(!is_null($campaignTheme) && ($campaignTheme->status == Theme::STATUS_ACTIVE)){
            $theme = $campaignTheme->filename;
        }
        else
        {
            $theme = 'default';
        }
        echo $this->renderPartial("@app/web/themes/{$theme}/index", ['campaign' => $campaign]);
        die;
    }

    /**
     * Campaign thank you page
     * @param $userAlias
     * @param $campaignAlias
     * @return string
     */
    public function actionThankyou($userAlias, $campaignAlias)
    {
        return $this->renderPartial('thankyou',
            [
                'campaign' => $this->getCampaign($userAlias, $campaignAlias)
            ]
        );
    }

    public function actionDonate($userAlias, $campaignAlias){
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        
        if ($campaign->formVisibility == false){
            $this->layout = 'donationWithoutBar';
            return $this->render('donationDisabled');
        }

        /*passing campaign to layout*/
        $this->campaign = $campaign;
        $this->layout = 'donation';
        $donation =  new Donation();
        $donation->scenario = $campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER ? Campaign::TYPE_PERSONAL_FUNDRAISER : 'default';
        $donation->createdDate = time();
        $donation->campaignId = $campaign->id;

        if (!\Yii::$app->user->isGuest){
            $donation->userId = \Yii::$app->user->id; 
        }

        if ($donation->load($_REQUEST))
        {
            $donation->nameFromForm = empty($donation->nameFromForm) ? Donation::ANONYMOUS_NAME : $donation->nameFromForm;

            if ($donation->save()){
                if ($firstGiving = $campaign->firstGiving) {

                    $firstGivingPayment = new FirstGivingPayment();

                    $thankYouPage = \Yii::$app->request->hostInfo . '/' .  $userAlias.'/'.$campaignAlias.'/thankyou';
                    $firstGivingPayment->setConfig(array_merge(\Yii::$app->params['firstGiving'], ['pb_success' => $thankYouPage, 'buttonText' => 'Donate']));
                    $firstGivingPayment->setDonation($donation);
                    $firstGivingPayment->setFirstGiving($firstGiving);

                    $formUrl = $firstGivingPayment->donationPayment();

                    return $this->render('firstgiving', ['url' => $formUrl, 'campaign' => $campaign, 'back_url' => "/{$userAlias}/{$campaignAlias}/donate"]);

                } else {
                    PullrPayment::donationPayment($donation);
                }
            }
        }


        //paypal success donate
        if (isset($_REQUEST['paymentSuccess']) && ($_REQUEST['paymentSuccess'] == 'true')){
            $payment = new PullrPayment();
            $payment->completePayment();
            $this->redirect('/'.$userAlias.'/'.$campaignAlias.'/thankyou');
        }

        if ($campaign->firstGiving) {
            $donation->setScenario('firstGiving');
        }

        return $this->render('donate', [
            'campaign' => $campaign,
            'donation' => $donation,
        ]);
    }
    
    public function actionJson($userAlias, $campaignAlias){
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        
        $response = $campaign->toArray(['amountRaised', 'goalAmount', 'startDate', 'endDate']);
        
        $response['numberOfDonations'] = $campaign->getDonations()->count();
        $response['numberOfDonors'] = $campaign->getDonations()->count('DISTINCT email');
        
        $donationsArray = [];
        
        $donations = $campaign->getDonations()->all();
        
        foreach ($donations as $donation){
            $donationsArray[] = $donation->toArray(['id', 'amount', 'nameFromForm', 'comments', 'paymentDate']);
        }
        $response['donations'] = $donationsArray;
        echo json_encode($response);
        die;
    }
       
}
