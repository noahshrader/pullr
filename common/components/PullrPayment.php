<?php

namespace common\components;

use common\models\notifications\RecentActivityNotification;
use common\components\message\ActivityMessage;
use OAuth\Common\Exception\Exception;
use common\models\Donation;
use common\models\Campaign;
use common\models\Plan;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Api\Transaction;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payee;
use PayPal\Api\Item;

defined('PP_CONFIG_PATH') or define('PP_CONFIG_PATH', __DIR__ . '/../config/paypal');
 
/*
 * Class for handling donation and recurring payments via PayPal
 */
class PullrPayment extends \yii\base\Component {

    public $apiContext;

    public function __construct($config = array()) 
    {
        parent::__construct($config);

        $this->apiContext = new ApiContext(new OAuthTokenCredential('', ''));
    }
    
    public function init()
    {
        parent::init();
        
        \PPHttpConfig::$DEFAULT_CURL_OPTS[CURLOPT_SSLVERSION] = 'all';
    }

    public static function getPaymentParamsForMoney($amount)
    {
        $params = [];
        switch ($amount)
        {
            case \Yii::$app->params['yearSubscription']:
                $params['days'] = 365.25;
                $params['subscription'] = Plan::SUBSCRIPTION_YEAR;
                $params['paymentType'] = \common\models\Payment::TYPE_PRO_YEAR;
                break;

            case \Yii::$app->params['monthSubscription']:
                $params['days'] = (365.25) / 12;
                $params['subscription'] = Plan::SUBSCRIPTION_MONTH;
                $params['paymentType'] = \common\models\Payment::TYPE_PRO_MONTH;
                break;

            default:
                throw new Exception("Wrong money amount");
        }
        return $params;
    }

    public function completePayment() 
    {
        // Get the payment Object by passing paymentId
        // payment id was previously stored in session in
        // CreatePaymentUsingPayPal.php
        $session = new \yii\web\Session();
        $paymentId =$session->get('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);

        // PaymentExecution object includes information necessary 
        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = (new PaymentExecution())->setPayerId($_GET['PayerID']);

        //Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        try 
        {
            $result = $payment->execute($execution, $this->apiContext);
        } 
        catch (\Exception $ex) 
        {
            return;
        }
        
        if ($result && $result->getState() == 'approved') 
        {
            $pay = \common\models\Payment::findOne(['payPalTransactionId' => $result->getId()]);
            
            if ($pay && $pay->status == \common\models\Payment::STATUS_PENDING) 
            {
                $pay->status = \common\models\Payment::STATUS_APPROVED;
                $pay->paymentDate = time();
                $pay->save();
                
                switch ($pay->type) 
                {
                    case \common\models\Payment::TYPE_PRO_MONTH:
                    case \common\models\Payment::TYPE_PRO_YEAR:
                        $id = $pay->user->id;
                        $plan = Plan::findOne($id);
                        $plan->prolong($pay->amount);
                        break;
                        
                    case \common\models\Payment::TYPE_DONATION:
                        $payer = $result->getPayer();
                        $payerInfo = $payer->getPayerInfo();
                        $donation = Donation::findOne($pay->relatedId);
                        $donation->paymentDate = $pay->paymentDate;
                        
                        if (!$donation->email)
                        {
                            $donation->email = strip_tags($payerInfo->getEmail());
                        }
                        
                        $donation->firstName = strip_tags($payerInfo->getFirstName());
                        $donation->lastName = strip_tags($payerInfo->getLastName());
                        $donation->save();
                        
                        Campaign::updateDonationStatistics($donation->campaignId);

                        // dashboard "Donation received" notification
                        RecentActivityNotification::createNotification(
                            \Yii::$app->user->id,
                            ActivityMessage::messageDonationReceived($donation)
                        );

                        // dashboard "Campaign goal reached" notification
                        $campaign = Campaign::findOne($donation->campaignId);
                        if (intval($campaign->amountRaised) >= intval($campaign->goalAmount))
                        {
                            RecentActivityNotification::createNotification(
                                \Yii::$app->user->id,
                                ActivityMessage::messageGoalReached($campaign)
                            );
                        }
                        break;
                }
            }
        }
    }

    /**
     * Fired when someone make donation for campaign
     * 
     * @param \common\models\Donation $donation
     */
    public static function donationPayment(Donation $donation) 
    {
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item = new Item();
        $item->setName('Donation for ' . $donation->campaign->name);
        $item->setCurrency('USD');
        $item->setQuantity(1);
        $item->setPrice($donation->amount);
        $itemList = new ItemList();
        $itemList->setItems([$item]);
        
        $amount = new Amount();
        $amount->setCurrency("USD");
        $amount->setTotal($donation->amount);

        $campaign = $donation->campaign;
        $email = $campaign->donationEmail;
        
        if (!$email)
        {
            throw new \Exception('Donation paypal account is not set');
        }
        
        $payee = new Payee();
        $payee->setEmail($email);
        
        self::makePayment($amount, $itemList, \common\models\Payment::TYPE_DONATION, $donation->id, $payee);
    }

    /**
     * Sends init request to PayPal to obtain pay key
     * Fired when someone wants to make donation for campaign
     *
     * @param Donation $donation
     * @param $returnUrl
     * @param $cancelUrl
     * @return string
     * @throws \Exception
     */
    public function initDonationPayment(Donation $donation, $returnUrl, $cancelUrl)
    {         
        if (!$donation->campaign->donationEmail)
        {
            throw new \Exception('Donation paypal account is not set');
        }
        
        // Default percent for guest user and user with Basic plan
        $percent = 0.2;
        if (!\Yii::$app->user->isGuest && (\Yii::$app->user->identity->getPlan() == Plan::PLAN_PRO)) 
        {
            $percent = 0.05;
        }
        
        // Donation tax receiver
        $pullr = new \Receiver();
        $pullr->amount = round($donation->amount * $percent, 2);
        $pullr->email = "pullforgood-facilitator@gmail.com";

        // Donation payee receiver
        $payee = new \Receiver();
        $payee->amount = $donation->amount;
        $payee->email = $donation->campaign->donationEmail;
        
        // Preparing request params to PayPal
        $payRequest = new \PayRequest();
        $payRequest->actionType = "PAY";
        $payRequest->currencyCode = "USD";
        $payRequest->returnUrl = $returnUrl;
        $payRequest->cancelUrl = $cancelUrl;
        $payRequest->memo = "Donation + Pullr fee";
        $payRequest->reverseAllParallelPaymentsOnError = true;
        $payRequest->requestEnvelope = new \RequestEnvelope("en_US");
        $payRequest->receiverList = new \ReceiverList([$pullr, $payee]);
        
        $service = new \AdaptivePaymentsService();
        
        try 
        {
            $response = $service->Pay($payRequest);
            if (strcasecmp($response->responseEnvelope->ack, "Failure") === 0)
            {
                throw new \Exception($response->error[0]->message);
            }
            
            // Create PayPal payment in Pullr database with 'pending' status
            $payDonation = new \common\models\Payment();
            $payDonation->userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $payDonation->type = \common\models\Payment::TYPE_DONATION;
            $payDonation->payKey = $response->payKey;
            $payDonation->amount = $payee->amount;
            $payDonation->relatedId = $donation->id;
            $payDonation->createdDate = time();
            $payDonation->save();
            
            // Create PayPal ***percent*** payment in Pullr database with 'pending' status
            $payPercent = new \common\models\Payment();
            $payPercent->userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $payPercent->type = \common\models\Payment::TYPE_DONATION_PERCENT;
            $payPercent->payKey = $response->payKey;
            $payPercent->amount = $pullr->amount;
            $payPercent->relatedId = $donation->id;
            $payPercent->createdDate = time();
            $payPercent->save();            
        }
        catch(\Exception $ex) 
        {
            \Yii::error($ex->getMessage(), 'PayPal');
            throw $ex;
        }        
        
        return $response->payKey;
    }
    
    /**
     * Summary of finishDonationPayment
     * @param mixed $payKey 
     * @throws Exception 
     * @return bool
     */
    public function finishDonationPayment($payKey)
    {        
        if(empty($payKey))
        {
            throw new \Exception("payKey cannot be null");
        }
        
        $paymentDetailsRequest = new \PaymentDetailsRequest(new \RequestEnvelope("en_US"));
        $paymentDetailsRequest->payKey = $payKey;
        $adaptivePaymentsService = new \AdaptivePaymentsService();
        $paymentDetailsResponse = $adaptivePaymentsService->PaymentDetails($paymentDetailsRequest);

        $donationId = 0;
        $paymentDate = time();

        foreach($paymentDetailsResponse->paymentInfoList->paymentInfo as $info)
        {
            if(strcasecmp($info->transactionStatus, "COMPLETED") === 0)
            {
                $payment = \common\models\Payment::findOne(["payKey" => $payKey, "amount" => $info->receiver->amount]);
                if(isset($payment) && ($payment->status === \common\models\Payment::STATUS_PENDING))
                {
                    $donationId = $payment->relatedId;

                    $payment->status = \common\models\Payment::STATUS_APPROVED;
                    $payment->paymentDate = $paymentDate;
                    $payment->payPalTransactionId = $info->transactionId;
                    $payment->save();
                }
            }
        }

        $donation = Donation::findOne($donationId);
        $donation->paymentDate = $paymentDate;
        $donation->save();

        Campaign::updateDonationStatistics($donation->campaignId);

        // Dashboard "Donation received" notification
        RecentActivityNotification::createNotification(\Yii::$app->user->id, ActivityMessage::messageDonationReceived($donation));

        // Dashboard "Campaign goal reached" notification
        $campaign = Campaign::findOne($donation->campaignId);
        if (intval($campaign->amountRaised) >= intval($campaign->goalAmount))
        {
            RecentActivityNotification::createNotification(\Yii::$app->user->id, ActivityMessage::messageGoalReached($campaign));
        }
        
        return true;
    }

    /**
     * Sends init request to PayPal for recurring payment
     * 
     * @param mixed $payAmount 
     * @return mixed
     */
    public static function initProSubscription($payAmount)
    {
        try
        {
            $payParams = PullrPayment::getPaymentParamsForMoney($payAmount);

            $paymentDetails = new \PaymentDetailsType();
            $itemDetails = new \PaymentDetailsItemType();
            $itemDetails->Name = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "\${$payAmount} for the year" : "\${$payAmount} for the month";
            $itemAmount = $payAmount;
            $itemDetails->Amount = $itemAmount;
            $itemQuantity = '1';
            $itemDetails->Quantity = $itemQuantity;
            $itemDetails->ItemCategory =  'Digital';
            $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

            $orderTotal = new \BasicAmountType('USD', $itemAmount * $itemQuantity);
            $paymentDetails->OrderTotal = $orderTotal;
            $paymentDetails->PaymentAction = 'Sale';

            $setECReqDetails = new \SetExpressCheckoutRequestDetailsType();
            $setECReqDetails->PaymentDetails[0] = $paymentDetails;
            $setECReqDetails->CancelURL = \Yii::$app->urlManager->createAbsoluteUrl('settings/index');
            $setECReqDetails->ReturnURL = \Yii::$app->urlManager->createAbsoluteUrl('settings/prosteptwo');

            $billingAgreementDetails = new \BillingAgreementDetailsType('RecurringPayments');
            $billingAgreementDetails->BillingAgreementDescription = 'Recurring payment';
            $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

            $setECReqType = new \SetExpressCheckoutRequestType($setECReqDetails);
            $setECReqType->Version = '104.0';

            $setECReq = new \SetExpressCheckoutReq();
            $setECReq->SetExpressCheckoutRequest = $setECReqType;

            $response = (new \PayPalAPIInterfaceServiceService())->SetExpressCheckout($setECReq);
            if($response->Ack == "Failure")
            {
                throw new \Exception($response->Errors[0]->LongMessage);
            }
            
            return $response;
        }
        catch (\Exception $ex)
        {
            \Yii::error($ex->getMessage(), 'PayPal');
            throw $ex;
        }
    }

    /**
     * Sends request to PayPal to finish subscription process
     * 
     * @param double $payAmount 
     * @param string $token 
     * @param int $PayerID 
     * @return mixed
     */
    public static function finishProSubscription($payAmount, $token, $PayerID)
    {
        try
        {
            $payParams = self::getPaymentParamsForMoney($payAmount);

            $paypalService = new \PayPalAPIInterfaceServiceService();

            $paymentDetails = new \PaymentDetailsType();
            $itemDetails = new \PaymentDetailsItemType();
            $itemDetails->Name = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "\${$payAmount} for the year" : "\${$payAmount} for the month";
            $itemAmount = $payAmount;
            $itemDetails->Amount = $itemAmount;
            $itemQuantity = '1';
            $itemDetails->Quantity = $itemQuantity;
            $itemDetails->ItemCategory = 'Digital';
            $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

            $orderTotal = new \BasicAmountType('USD', $itemAmount * $itemQuantity);
            $paymentDetails->OrderTotal = $orderTotal;
            $paymentDetails->PaymentAction = 'Sale';
            $paymentDetails->NotifyURL = \Yii::$app->urlManager->createAbsoluteUrl('ipn/notify');

            $DoECRequestDetails = new \DoExpressCheckoutPaymentRequestDetailsType();
            $DoECRequestDetails->PayerID = $PayerID;
            $DoECRequestDetails->Token = $token;
            $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

            $DoECRequest = new \DoExpressCheckoutPaymentRequestType($DoECRequestDetails);
            $DoECRequest->Version = '104.0';

            $DoECReq = new \DoExpressCheckoutPaymentReq();
            $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

            $DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);

            $initPaymentInfo = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0];

            //subscribe user to recurring payment if successfully billed for the first month\year
            if (($DoECResponse->Ack === 'Success') && ($initPaymentInfo->PaymentStatus === 'Completed'))
            {
                //create a transaction record in payments table
                $payment = new \common\models\Payment();
                $payment->status = \common\models\Payment::STATUS_APPROVED;
                $payment->userId = \Yii::$app->user->identity->id;
                $payment->amount = intval($payAmount);
                $payment->payPalTransactionId = $initPaymentInfo->TransactionID;
                $payment->createdDate = time();
                $payment->paymentDate = time();
                $payment->type = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? \common\models\Payment::TYPE_PRO_YEAR : \common\models\Payment::TYPE_PRO_MONTH;
                $payment->save();

                //prolong user Pro account
                Plan::findOne(\Yii::$app->user->identity->id)->prolong($payAmount);

                //preparations to create recurring subscription
                $dateInterval = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? (new \DateInterval('P1Y')) : (new \DateInterval('P1M'));
                $profileDetails = new \RecurringPaymentsProfileDetailsType(
                    (new \DateTime())
                        ->setTimezone(new \DateTimeZone(\Yii::$app->user->identity->getTimezone()))
                        ->setTimestamp(time())
                        ->add($dateInterval)
                        ->format('c')
                );

                $paymentBillingPeriod = new \BillingPeriodDetailsType(
                    $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "Year" : "Month",
                    1,
                    new \BasicAmountType("USD", $payAmount)
                );

                $scheduleDetails = new \ScheduleDetailsType("Recurring payment", $paymentBillingPeriod);

                $createRPProfileRequestDetails = new \CreateRecurringPaymentsProfileRequestDetailsType($profileDetails, $scheduleDetails);
                $createRPProfileRequestDetails->Token = $token;

                $createRPProfileRequest = new \CreateRecurringPaymentsProfileRequestType();
                $createRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $createRPProfileRequestDetails;

                $createRPProfileReq = new \CreateRecurringPaymentsProfileReq();
                $createRPProfileReq->CreateRecurringPaymentsProfileRequest = $createRPProfileRequest;

                return (new \PayPalAPIInterfaceServiceService())->CreateRecurringPaymentsProfile($createRPProfileReq);
            }
        }
        catch (\Exception $ex)
        {
            \Yii::error($ex->getMessage(), 'PayPal');
            throw $ex;
        }
    }

    /**
     * Sends request to PayPal to cancel recurring payment profile
     * 
     * @param int $profileId 
     * @return mixed
     */
    public static function deactivateProSubscription($profileId)
    {
        $details = new \ManageRecurringPaymentsProfileStatusRequestDetailsType();
        $details->Action = 'Cancel';
        $details->ProfileID = $profileId;

        $statusRequest = new \ManageRecurringPaymentsProfileStatusRequestType();
        $statusRequest->ManageRecurringPaymentsProfileStatusRequestDetails = $details;

        $profileStatus = new \ManageRecurringPaymentsProfileStatusReq();
        $profileStatus->ManageRecurringPaymentsProfileStatusRequest = $statusRequest;

        return (new \PayPalAPIInterfaceServiceService())->ManageRecurringPaymentsProfileStatus($profileStatus);
    }
}