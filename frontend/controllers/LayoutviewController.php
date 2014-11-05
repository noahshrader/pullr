<?php

namespace frontend\controllers;

use common\models\Theme;
use common\models\User;
use common\models\Campaign;
use common\models\Donation;
use common\components\PullrPayment;
use common\components\FirstGivingPayment;
use common\components\PullrUtils;
use yii\web\Request;
use yii\helpers\Url;

/**
 * Controller to view exported layout donations
 */
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

    public function actionView($userAlias, $campaignAlias) 
    {
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
     * 
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
    

    /**
     * Campaign donation page
     * Handles both standart and Firstgiving campaign types
     * 
     * @todo break method into smaller actions
     * @param mixed $userAlias 
     * @param mixed $campaignAlias 
     * @return mixed
     */
    public function actionDonate($userAlias, $campaignAlias)
    {
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        
        if(isset($_GET["success"]) && ($_GET["success"] == "true"))
        {
            $this->redirect(Url::to([sprintf("/%s/%s/thankyou", $userAlias, $campaignAlias)]));
            \Yii::$app->end();
        }
        
        if ($campaign->formVisibility == false)
        {
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

        if (!\Yii::$app->user->isGuest)
        {
            $donation->userId = \Yii::$app->user->id; 
        }

        if ($donation->load($_REQUEST) && $donation->save())
        {
            if ($firstGiving = $campaign->firstGiving) 
            {
                
                $_SESSION['Donation'] = [
                        'nameFromForm' => $donation->nameFromForm == Donation::ANONYMOUS_NAME ? '' : $donation->nameFromForm,
                        'email' => $donation->email,
                        'comments' => $donation->comments
                    ];
                
                $firstGivingPayment = new FirstGivingPayment();

                $thankYouPage = \Yii::$app->request->hostInfo . '/' .  $userAlias.'/'.$campaignAlias.'/thankyou';
                $firstGivingPayment->setConfig(array_merge(\Yii::$app->params['firstGiving'], ['pb_success' => $thankYouPage, 'buttonText' => 'Donate']));
                $firstGivingPayment->setDonation($donation);
                $firstGivingPayment->setFirstGiving($firstGiving);

                $formUrl = $firstGivingPayment->donationPayment();

                return $this->render('firstgiving', ['url' => $formUrl, 'campaign' => $campaign, 'back_url' => "/{$userAlias}/{$campaignAlias}/donate"]);
            } 
            else 
            {
                $relativeUrl = sprintf("/%s/%s/donate", $userAlias, $campaignAlias);
                $returnUrl = \Yii::$app->urlManager->createAbsoluteUrl([$relativeUrl, "success" => "true"]);
                $cancelUrl = \Yii::$app->urlManager->createAbsoluteUrl([$relativeUrl, "success" => "false"]);
                $payPalHost = \Yii::$app->params['payPalHost'];
                $apiConfig = $apiConfig = \Yii::$app->params['payPal'];
                $payKey = (new PullrPayment($apiConfig))->initDonationPayment($donation, $returnUrl, $cancelUrl);
                (new \yii\web\Session())->set("payKey", $payKey);

                $this->redirect(sprintf("%s/cgi-bin/webscr?cmd=_ap-payment&paykey=%s", $payPalHost, $payKey));
                \Yii::$app->end();
            }
        }

        if ($campaign->firstGiving) {
            if (isset($_SESSION['Donation']))
            {
                $donation->load($_SESSION);
            }
            
            $donation->setScenario('firstGiving');
        }

        return $this->render('donate', [
            'campaign' => $campaign,
            'donation' => $donation,
        ]);
    }
    
    public function actionJson($userAlias, $campaignAlias){
        $campaign = $this->getCampaign($userAlias, $campaignAlias);
        //format number
        $campaign->amountRaised = PullrUtils::formatNumber($campaign->amountRaised, 2);
        $campaign->goalAmount = PullrUtils::formatNumber($campaign->goalAmount, 2);
        
        $response = $campaign->toArray(['amountRaised', 'goalAmount', 'startDate', 'endDate']);
        
        $response['numberOfDonations'] = $campaign->getDonations()->count();
        $response['numberOfDonors'] = $campaign->getDonations()->count('DISTINCT email');
        
        $donationsArray = [];
        
        $donations = $campaign->getDonations()->all();
        
        foreach ($donations as $donation){
            $donation->amount = PullrUtils::formatNumber($donation->amount, 2);
            $donationsArray[] = $donation->toArray(['id', 'amount', 'nameFromForm', 'comments', 'paymentDate']);
        }
        $response['donations'] = $donationsArray;
        echo json_encode($response);
        die;
    }    
}