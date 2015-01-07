<?php

namespace common\components;

use common\models\notifications\RecentActivityNotification;
use common\components\message\ActivityMessage;
use common\models\Donation;
use common\models\Campaign;
use common\models\Plan;
use yii\helpers\VarDumper;

/*
 * Class for handling donations and recurring payments via PayPal
 */
class PullrPayment extends \yii\base\Component
{
    private $payPalConfig;

    /**
     * Creates class with PayPal config injected
     *
     * @param array $payPalConfig
     * @param array $config
     */
    public function __construct(array $payPalConfig, $config = [])
    {
        $this->payPalConfig = $payPalConfig;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \PPHttpConfig::$DEFAULT_CURL_OPTS[CURLOPT_SSLVERSION] = 'all';

    }

    /**
     * Returns subscriptions params by pay amount
     *
     * @param $amount
     * @return array
     * @throws \Exception
     */
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

            case '3.99':
            case \Yii::$app->params['monthSubscription']:
                $params['days'] = (365.25) / 12;
                $params['subscription'] = Plan::SUBSCRIPTION_MONTH;
                $params['paymentType'] = \common\models\Payment::TYPE_PRO_MONTH;
                break;

            default:
                throw new \Exception("Wrong money amount");
        }
        return $params;
    }

    /**
     * Returns donation percent depending on campaign owner plan
     *
     * @param Donation $donation
     * @return float
     */
    private function calculatePercent(Donation $donation)
    {
        $percent = 0.02;
        if ($donation->campaign->user->getPlan() == Plan::PLAN_PRO)
        {
            $percent = 0.01;
        }

        return $percent;
    }

    /**
     * Returns fee receiver object with amount and email set
     *
     * @param Donation $donation
     * @return \Receiver
     */
    private function prepareFeeReceiver(Donation $donation)
    {
        $feeReceiver = new \Receiver(round($donation->amount * $this->calculatePercent($donation), 2));
        $feeReceiver->email = \Yii::$app->params['payPalDonationFeeReceiver'];

        return $feeReceiver;
    }

    /**
     * Returns donation receiver object with amount and email set
     *
     * @param Donation $donation
     * @return \Receiver
     */
    private function prepareDonationReceiver(Donation $donation)
    {
        $donationReceiver = new \Receiver($donation->amount);
        $donationReceiver->email = $donation->campaign->donationEmail;

        return $donationReceiver;
    }

    /**
     * Returns object with payment params set
     *
     * @param array $receivers
     * @param $returnUrl
     * @param $cancelUrl
     * @return \PayRequest
     */
    private function preparePayRequest(array $receivers, $returnUrl, $cancelUrl)
    {
        $payRequest = new \PayRequest();
        $payRequest->actionType = "PAY";
        $payRequest->currencyCode = "USD";
        $payRequest->returnUrl = $returnUrl;
        $payRequest->cancelUrl = $cancelUrl;
        $payRequest->ipnNotificationUrl = \Yii::$app->urlManager->createAbsoluteUrl('ipn/notifyadaptive');
        $payRequest->reverseAllParallelPaymentsOnError = true;
        $payRequest->requestEnvelope = new \RequestEnvelope("en_US");
        $payRequest->receiverList = new \ReceiverList($receivers);

        return $payRequest;
    }

    /**
     * Creates 'pending' db records for fee payment and donation payment
     *
     * @param Donation $donation
     * @param \PayResponse $response
     * @param \Receiver $feeReceiver
     * @param \Receiver $donationReceiver
     */
    private function createPendingPayments(Donation $donation, \PayResponse $response, \Receiver $feeReceiver, \Receiver  $donationReceiver)
    {
        $donationPayment = new \common\models\Payment();
        $donationPayment->userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
        $donationPayment->type = \common\models\Payment::TYPE_DONATION;
        $donationPayment->payKey = $response->payKey;
        $donationPayment->amount = $donationReceiver->amount;
        $donationPayment->relatedId = $donation->id;
        $donationPayment->createdDate = time();
        $donationPayment->save();

        if ($feeReceiver->amount > 0)
        {
            $percentPayment = new \common\models\Payment();
            $percentPayment->userId = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $percentPayment->type = \common\models\Payment::TYPE_DONATION_PERCENT;
            $percentPayment->payKey = $response->payKey;
            $percentPayment->amount = $feeReceiver->amount;
            $percentPayment->relatedId = $donation->id;
            $percentPayment->createdDate = time();
            $percentPayment->save();
        }
    }

    /**
     * Sets payment to 'approved' state
     *
     * @param $payKey
     * @param $payedItem
     * @param $timestamp
     * @return mixed
     */
    private function approvePayments($payKey, $payedItem, $timestamp)
    {
        $payment = \common\models\Payment::findOne(["payKey" => $payKey, "amount" => $payedItem->receiver->amount]);
        if(isset($payment) && ($payment->status === \common\models\Payment::STATUS_PENDING))
        {
            $payment->status = \common\models\Payment::STATUS_APPROVED;
            $payment->paymentDate = $timestamp;
            $payment->payPalTransactionId = $payedItem->transactionId;
            $payment->save();

            return $payment->relatedId;
        }
    }

    /**
     * Sets donation's paymentDate to timestamp
     *
     * @param $donationId
     * @param $paymentDate
     */
    private function makeDonationProcessed($donationId, $paymentDate)
    {
        $donation = Donation::findOne($donationId);
        if (isset($donation) && !$donation->isPaid())
        {
            $donation->paymentDate = $paymentDate;
            $donation->save();

            Campaign::updateDonationStatistics($donation->campaignId);

            // Dashboard "Donation received" notification
            RecentActivityNotification::createNotification($donation->campaign->userId, ActivityMessage::messageDonationReceived($donation));

            // Dashboard "Campaign goal reached" notification
            $campaign = Campaign::findOne($donation->campaignId);
            if (intval($campaign->amountRaised) >= intval($campaign->goalAmount))
            {
                RecentActivityNotification::createNotification($donation->campaign->userId, ActivityMessage::messageGoalReached($campaign));
            }
        }
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
            throw new \Exception('Donation PayPal email is not set');
        }

        $feeReceiver = $this->prepareFeeReceiver($donation);
        $donationReceiver = $this->prepareDonationReceiver($donation);
        $receivers = $feeReceiver->amount > 0 ? [$feeReceiver, $donationReceiver] : [$donationReceiver];
        $payRequest = $this->preparePayRequest($receivers, $returnUrl, $cancelUrl);

        try
        {
            $service = new \AdaptivePaymentsService($this->payPalConfig);
            $response = $service->Pay($payRequest);

            if (strcasecmp($response->responseEnvelope->ack, "Failure") === 0)
            {
                throw new \Exception(array_pop($response->error)->message);
            }

            $this->createPendingPayments($donation, $response, $feeReceiver, $donationReceiver);
        }
        catch(\Exception $ex)
        {
            \Yii::error($ex->getMessage(), 'PayPal');
            throw $ex;
        }

        return $response->payKey;
    }

    /**
     * Retrieves payment status by pay key and sets payments status to 'approved'
     *
     * @param $payKey
     * @return bool
     * @throws \Exception
     */
    public function finishDonationPayment($payKey)
    {
        if(empty($payKey))
        {
            throw new \Exception("payKey cannot be null");
        }

        try
        {
            $adaptivePaymentsService = new \AdaptivePaymentsService($this->payPalConfig);
            $paymentDetailsRequest = new \PaymentDetailsRequest(new \RequestEnvelope("en_US"));
            $paymentDetailsRequest->payKey = $payKey;
            $paymentDetailsResponse = $adaptivePaymentsService->PaymentDetails($paymentDetailsRequest);
        }
        catch(\Exception $ex)
        {
            \Yii::error($ex->getMessage(), 'PayPal');
            throw $ex;
        }

        $donationId = null;
        $paymentDate = time();

        foreach($paymentDetailsResponse->paymentInfoList->paymentInfo as $info)
        {
            if(strcasecmp($info->transactionStatus, "COMPLETED") === 0)
            {
                $donationId = $this->approvePayments($payKey, $info, $paymentDate);
            }
        }
        $donation = Donation::findOne($donationId);
        if (isset($donation) && isset($paymentDetailsResponse->senderEmail)){
            $donation->email = $paymentDetailsResponse->senderEmail;
            $donation->save();
        }

        $this->makeDonationProcessed($donationId, $paymentDate);

        return true;
    }

    /**
     * Sends init request to PayPal for recurring payment
     *
     * @param $payAmount
     * @throws \Exception
     */
    public function initProSubscription($payAmount)
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

            $response = (new \PayPalAPIInterfaceServiceService($this->payPalConfig))->SetExpressCheckout($setECReq);
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
     * @param $payAmount
     * @param $token
     * @param $PayerID
     * @throws \Exception
     */
    public function finishProSubscription($payAmount, $token, $PayerID)
    {
        try
        {
            $payParams = self::getPaymentParamsForMoney($payAmount);

            $paypalService = new \PayPalAPIInterfaceServiceService($this->payPalConfig);

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

            if($DoECResponse->Ack == "Failure")
            {
                throw new \Exception(array_pop($DoECResponse->Errors)->LongMessage);
            }

            $initPaymentInfo = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0];
            //subscribe user to recurring payment if successfully billed for the first month\year
            if (($DoECResponse->Ack === 'Success') && ($initPaymentInfo->PaymentStatus === 'Completed'))
            {
                //create a transaction record in payments table
                $payment = new \common\models\Payment();
                $payment->status = \common\models\Payment::STATUS_APPROVED;
                $payment->userId = \Yii::$app->user->identity->id;
                $payment->amount = $payAmount;
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

                return (new \PayPalAPIInterfaceServiceService($this->payPalConfig))->CreateRecurringPaymentsProfile($createRPProfileReq);
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
    public function deactivateProSubscription($profileId)
    {
        $details = new \ManageRecurringPaymentsProfileStatusRequestDetailsType();
        $details->Action = 'Cancel';
        $details->ProfileID = $profileId;

        $statusRequest = new \ManageRecurringPaymentsProfileStatusRequestType();
        $statusRequest->ManageRecurringPaymentsProfileStatusRequestDetails = $details;

        $profileStatus = new \ManageRecurringPaymentsProfileStatusReq();
        $profileStatus->ManageRecurringPaymentsProfileStatusRequest = $statusRequest;

        return (new \PayPalAPIInterfaceServiceService($this->payPalConfig))->ManageRecurringPaymentsProfileStatus($profileStatus);
    }
}