<?php

namespace frontend\controllers;

define('PP_CONFIG_PATH', '/var/www/pullr/common/config/paypal');

use common\models\RecurringProfile;
use frontend\models\site\DeactivatePro;
use Yii;
use \yii\web\Session;
use \common\models\Payment;
use frontend\models\site\ChangePasswordForm;
use common\models\Plan;
use frontend\models\site\DeactivateAccount;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\mail\Mail;
use common\components\PullrPayment;

class SettingsController extends FrontendController {

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;
        $user->setScenario('settings');

        if (isset($_POST['User'])){
            $user->setAttributes($_POST['User']);
            $user->save();
        }

        $notification = $user->notification;
        $notification->load($_POST) && $notification->save($_POST);

        $changePasswordForm = new ChangePasswordForm();
        if ($changePasswordForm->load($_POST) && $changePasswordForm->oldPassword) {
            $changePasswordForm->validatePassword();
            $changePasswordForm->validateNewPassword();
            if (!$changePasswordForm->getErrors()) {
                $user->setNewPassword($changePasswordForm->newPassword);
                $user->save();
                $changePasswordForm = new ChangePasswordForm();
                $changePasswordForm->success = true;
            }
        }

        /* account subscriptions */
        if (isset($_POST['subscription'])) {
            $payment = new PullrPayment();
            $payment->subscribeToPlan($_POST['subscription']);
        }
        if (isset($_REQUEST['paymentSuccess']) && ($_REQUEST['paymentSuccess'] == 'true')){
            $payment = new PullrPayment();
            $payment->completePayment();
            $this->redirect('app/settings');
        }

        return $this->render('index', [
                    'user' => $user,
                    'notification' => $notification,
                    'changePasswordForm' => $changePasswordForm
        ]);
    }

    /**
     * that is for debug purposes and only availabe for user's id<10
     */
    public function actionDeactivatepro(){

        $recurringProfile = RecurringProfile::findOne(['userId' => Yii::$app->user->identity->id]);

        if(isset($recurringProfile)){
            $paypalService = new \PayPalAPIInterfaceServiceService();
            $requestDetails = new \ManageRecurringPaymentsProfileStatusRequestDetailsType();
            $requestDetails->Action =  'Cancel';
            $requestDetails->ProfileID =  $recurringProfile->profileId;
            $profileStatusRequest = new \ManageRecurringPaymentsProfileStatusRequestType();
            $profileStatusRequest->ManageRecurringPaymentsProfileStatusRequestDetails = $requestDetails;

            $manageRPPStatusReq = new \ManageRecurringPaymentsProfileStatusReq();
            $manageRPPStatusReq->ManageRecurringPaymentsProfileStatusRequest = $profileStatusRequest;

            $paypalService->ManageRecurringPaymentsProfileStatus($manageRPPStatusReq);

            $deactivate = new DeactivatePro();
            if ($deactivate->load($_POST) && $deactivate->save()) {

                $content = $this->renderPartial('@console/views/mail/deactivationEmail', [
                    'reason' => $deactivate->getReason(),
                    'user' => $deactivate->user
                ]);

                Mail::sendMail(\Yii::$app->params['adminEmails'], 'User deactivated pro-account', $content, 'proAccountDeactivation');
            }

            (new Session())->setFlash("pro_deactivate", "");
        }

        $this->redirect('index');
    }
    
    /**
     * that method set request for account deactivation, and logout user
     * then if user will not login in 30 days his account will deactivated
     */
    public function actionDeactivate() {
        $deactivate = new DeactivateAccount();
        if (Yii::$app->request->isAjax) {
            $deactivate->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($deactivate);
        } else {
            /* in fact it should be true, as it was verified by ajax before sending */
            if ($deactivate->load($_POST) && $deactivate->save()) {
                $content = $this->renderPartial('@console/views/mail/deactivationEmail', [
                    'reason' => $deactivate->reason,
                    'user' => $deactivate->user
                ]);

                Mail::sendMail(\Yii::$app->params['adminEmails'], 'User deactivated account', $content, 'deactivatedAccount');
                Yii::$app->getUser()->logout(true);
                $this->redirect('/');
            }
        }
    }

    public function actionProstepone(){
        if (isset($_POST['subscription'])) {
            $payAmount = $_POST['subscription'];
            $payParams = PullrPayment::getPaymentParamsForMoney($payAmount);

            (new Session())->set('money_amount', $payAmount);

            $paypalService = new \PayPalAPIInterfaceServiceService();

            $paymentDetails = new \PaymentDetailsType();
            $itemDetails = new \PaymentDetailsItemType();
            $itemDetails->Name = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "\${$payAmount} for the year" : "\${$payAmount} for the month";
            $itemAmount = $payAmount;
            $itemDetails->Amount = $itemAmount;
            $itemQuantity = '1';
            $itemDetails->Quantity = $itemQuantity;
            $itemDetails->ItemCategory =  'Digital';
            $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

            $orderTotal = new \BasicAmountType();
            $orderTotal->currencyID = 'USD';
            $orderTotal->value = $itemAmount * $itemQuantity;

            $paymentDetails->OrderTotal = $orderTotal;
            $paymentDetails->PaymentAction = 'Sale';

            $setECReqDetails = new \SetExpressCheckoutRequestDetailsType();
            $setECReqDetails->PaymentDetails[0] = $paymentDetails;
            $setECReqDetails->CancelURL = Yii::$app->urlManager->createAbsoluteUrl('settings/index');
            $setECReqDetails->ReturnURL = Yii::$app->urlManager->createAbsoluteUrl('settings/prosteptwo');

            $billingAgreementDetails = new \BillingAgreementDetailsType('RecurringPayments');
            $billingAgreementDetails->BillingAgreementDescription = 'Recurring payment';
            $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

            $setECReqType = new \SetExpressCheckoutRequestType();
            $setECReqType->Version = '104.0';
            $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

            $setECReq = new \SetExpressCheckoutReq();
            $setECReq->SetExpressCheckoutRequest = $setECReqType;

            $setECResponse = $paypalService->SetExpressCheckout($setECReq);

            $payPalHost = \Yii::$app->params['payPalHost'];
            $this->redirect("$payPalHost/incontext?token={$setECResponse->Token}");
        }
    }

    public function actionProsteptwo($token, $PayerID){
        $session = new Session();
        $payAmount = $session->get("money_amount");
        $payParams = PullrPayment::getPaymentParamsForMoney($payAmount);

        $paypalService = new \PayPalAPIInterfaceServiceService();

        $paymentDetails= new \PaymentDetailsType();
        $itemDetails = new \PaymentDetailsItemType();
        $itemDetails->Name = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "\${$payAmount} for the year" : "\${$payAmount} for the month";
        $itemAmount = $payAmount;
        $itemDetails->Amount = $itemAmount;
        $itemQuantity = '1';
        $itemDetails->Quantity = $itemQuantity;
        $itemDetails->ItemCategory =  'Digital';
        $paymentDetails->PaymentDetailsItem[0] = $itemDetails;

        $orderTotal = new \BasicAmountType();
        $orderTotal->currencyID = 'USD';
        $orderTotal->value = $itemAmount * $itemQuantity;

        $paymentDetails->OrderTotal = $orderTotal;
        $paymentDetails->PaymentAction = 'Sale';
        $paymentDetails->NotifyURL = Yii::$app->urlManager->createAbsoluteUrl('ipn/notify');

        $DoECRequestDetails = new \DoExpressCheckoutPaymentRequestDetailsType();
        $DoECRequestDetails->PayerID = $PayerID;
        $DoECRequestDetails->Token = $token;
        $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

        $DoECRequest = new \DoExpressCheckoutPaymentRequestType();
        $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
        $DoECRequest->Version = '104.0';

        $DoECReq = new \DoExpressCheckoutPaymentReq();
        $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

        $DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);

        $initPaymentInfo = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0];

        //subscribe user to recurring payment if successfully billed for the first month\year
        if(($DoECResponse->Ack === 'Success') && ($initPaymentInfo->PaymentStatus === 'Completed'))
        {
            //create a transaction record in payments table
            $payment = new Payment();
            $payment->status = Payment::STATUS_APPROVED;
            $payment->userId = \Yii::$app->user->identity->id;
            $payment->amount = intval($payAmount);
            $payment->paypalId = $initPaymentInfo->TransactionID;
            $payment->createdDate = time();
            $payment->type = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? Payment::TYPE_PRO_YEAR : Payment::TYPE_PRO_MONTH;
            $payment->save();

            //prolong user Pro account
            Plan::findOne(\Yii::$app->user->identity->id)->prolong((new Session())->get("money_amount"));

            //preparations to create recurring subscription
            $profileDetails = new \RecurringPaymentsProfileDetailsType();
            $dateInterval = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? (new \DateInterval('P1Y')) : (new \DateInterval('P1M'));
            $profileDetails->BillingStartDate = (new \DateTime())
                ->setTimezone(new \DateTimeZone(Yii::$app->user->identity->getTimezone()))
                ->setTimestamp(time())
                ->add($dateInterval)
                ->format('c');

            $paymentBillingPeriod = new \BillingPeriodDetailsType();
            $paymentBillingPeriod->BillingFrequency = 1;
            $paymentBillingPeriod->BillingPeriod = $payParams['subscription'] == Plan::SUBSCRIPTION_YEAR ? "Year" : "Month";
            $paymentBillingPeriod->Amount = new \BasicAmountType("USD", $payAmount);

            $scheduleDetails = new \ScheduleDetailsType();
            $scheduleDetails->Description = "Recurring payment";
            $scheduleDetails->PaymentPeriod = $paymentBillingPeriod;

            $createRPProfileRequestDetails = new \CreateRecurringPaymentsProfileRequestDetailsType();
            $createRPProfileRequestDetails->Token = $token;

            $createRPProfileRequestDetails->ScheduleDetails = $scheduleDetails;
            $createRPProfileRequestDetails->RecurringPaymentsProfileDetails = $profileDetails;

            $createRPProfileRequest = new \CreateRecurringPaymentsProfileRequestType();
            $createRPProfileRequest->CreateRecurringPaymentsProfileRequestDetails = $createRPProfileRequestDetails;

            $createRPProfileReq = new \CreateRecurringPaymentsProfileReq();
            $createRPProfileReq->CreateRecurringPaymentsProfileRequest = $createRPProfileRequest;

            $paypalService = new \PayPalAPIInterfaceServiceService();
            $response = $paypalService->CreateRecurringPaymentsProfile($createRPProfileReq);

            if ($response->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus === 'ActiveProfile'){
                $recurringProfile = RecurringProfile::findOne(['userId' => \Yii::$app->user->identity->id]);

                if (isset($recurringProfile)){
                    $recurringProfile->profileId = $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                    $recurringProfile->save();
                }
                else{
                    $recurringProfile = new RecurringProfile();
                    $recurringProfile->profileId = $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                    $recurringProfile->userId = \Yii::$app->user->identity->id;
                    $recurringProfile->save();
                }

                $session->setFlash("pro_success", "");
                $this->redirect("index");
            }
        }
    }
}
