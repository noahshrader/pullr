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

    public $clientId;
    public $clientSecret;
    public $apiContext;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->clientId = \Yii::$app->params['paypalClientId'];
        $this->clientSecret = \Yii::$app->params['paypalClientSecret'];
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->clientId, $this->clientSecret));
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

    public function completePayment() {
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
        $execution = new PaymentExecution();
        $execution->setPayer_id($_GET['PayerID']);

        //Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\Exception $ex) {
            return;
        }
        if ($result && $result->getState() == 'approved') {
            $pay = \common\models\Payment::findOne(['paypalId' => $result->getId()]);
            if ($pay && $pay->status == \common\models\Payment::STATUS_PENDING) {
                $pay->status = \common\models\Payment::STATUS_APPROVED;
                $pay->paymentDate = time();
                $pay->save();
                switch ($pay->type) {
                    case \common\models\Payment::TYPE_PRO_MONTH:
                    case \common\models\Payment::TYPE_PRO_YEAR:
                        $id = $pay->user->id;
                        $plan = Plan::findOne($id);
                        $plan->prolong($pay->amount);
                        break;
                    case \common\models\Payment::TYPE_DONATION:
                        $payer = $result->getPayer();
                        $payerInfo = $payer->getPayer_info();
                        $donation = Donation::findOne($pay->relatedId);
                        $donation->paymentDate = $pay->paymentDate;
                        if (!$donation->email){
                            $donation->email = strip_tags($payerInfo->getEmail());
                        }
                        $donation->firstName = strip_tags($payerInfo->getFirst_name());
                        $donation->lastName = strip_tags($payerInfo->getLast_name());
                        $donation->save();
                        Campaign::updateDonationStatistics($donation->campaignId);

                        // dashboard "Donation received" notification
                        RecentActivityNotification::createNotification(
                            \Yii::$app->user->id,
                            ActivityMessage::messageDonationReceived($donation)
                        );

                        // dashboard "Campaign goal reached" notification
                        $campaign = Campaign::findOne($donation->campaignId);
                        if (intval($campaign->amountRaised) >= intval($campaign->goalAmount)){
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
     * fired when someone make donation for campaign
     * @param \common\models\Donation $donation
     */
    public static function donationPayment(Donation $donation) {
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
        if (!$email){
            throw new \Exception('Donation paypal account is not set');
        }
        $payee = new Payee();
        $payee->setEmail($email);
        
        self::makePayment($amount, $itemList, \common\models\Payment::TYPE_DONATION, $donation->id, $payee);
    }

    public function subscribeToPlan($moneyAmount) {
        return;
        $params = self::getPaymentParamsForMoney($moneyAmount);

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item = new Item();
        $item->setName('Pro account for ' . $params['subscription'])
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($moneyAmount);
        $itemList = new ItemList();
        $itemList->setItems([$item]);


        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal($moneyAmount);

        self::makePayment($amount, $itemList, $params['paymentType']);
    }

    public static function makePayment($amount, $itemList, $paymentType, $relatedId = null, $payee = null) {
        // A resource representing a Payer that funds a payment
        // For paypal account payments, set payment method
        // to 'paypal'.
        $payer = new Payer();
        $payer->setPayment_method("paypal");

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setItem_list($itemList);

        if ($payee){
            $transaction->setPayee($payee);
        }
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $baseUrl = preg_replace('/\?.*/', '', $baseUrl);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturn_url($baseUrl . '?paymentSuccess=true');
        $redirectUrls->setCancel_url($baseUrl . '?paymentSuccess=false');

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setRedirect_urls($redirectUrls);
        $payment->setTransactions([$transaction]);

        $pullrPayment = new PullrPayment;
        try {

            $payment->create($pullrPayment->apiContext);
            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getLinks()
            // method
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirectUrl = $link->getHref();
                    break;
                }
            }
            
            $session = new \yii\web\Session();
            // ### Redirect buyer to PayPal website
            // Save the payment id so that you can 'complete' the payment
            // once the buyer approves the payment and is redirected
            // back to your website.
            //

            $session->set('paymentId', $payment->getId());
            $pay = new \common\models\Payment();
            $pay->paypalId = $payment->getId();
            if (!\Yii::$app->user->isGuest) {
                $pay->userId = \Yii::$app->user->id;
            }
            $pay->amount = $amount->getTotal();
            $pay->type = $paymentType;
            $pay->relatedId = $relatedId;
            $pay->createdDate = time();
            $pay->save();

            if (isset($redirectUrl)) {
                header("Location: $redirectUrl");
                exit;
            }
        }
        catch (\PPConnectionException $ex)
        {
            \Yii::error($ex->getData());
            throw $ex;
        }
        catch (\Exception $ex)
        {
            \Yii::error($ex->getMessage());
            throw $ex;
        }
    }

    public static function initProSubscription($payAmount){
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

        return (new \PayPalAPIInterfaceServiceService())->SetExpressCheckout($setECReq);
    }

    public static function finishProSubscription($payAmount, $token, $PayerID)
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
        if (($DoECResponse->Ack === 'Success') && ($initPaymentInfo->PaymentStatus === 'Completed')) {
            //create a transaction record in payments table
            $payment = new \common\models\Payment();
            $payment->status = \common\models\Payment::STATUS_APPROVED;
            $payment->userId = \Yii::$app->user->identity->id;
            $payment->amount = intval($payAmount);
            $payment->paypalId = $initPaymentInfo->TransactionID;
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
