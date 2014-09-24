<?php

namespace frontend\controllers;

use common\models\RecurringProfile;
use frontend\models\site\DeactivatePro;
use Yii;
use \yii\web\Session;
use frontend\models\site\ChangePasswordForm;
use frontend\models\site\DeactivateAccount;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\mail\Mail;
use common\components\PullrPayment;

defined('PP_CONFIG_PATH') or define ('PP_CONFIG_PATH', \Yii::getAlias('@app').'/../common/config/paypal');

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
            (new Session())->set('money_amount', $payAmount);

            $setECResponse = PullrPayment::initProSubscription($payAmount);

            $payPalHost = \Yii::$app->params['payPalHost'];
            $this->redirect("$payPalHost/incontext?token={$setECResponse->Token}");
        }
    }

    public function actionProsteptwo($token, $PayerID)
    {
        $session = new Session();
        $payAmount = $session->get("money_amount");

        $response = PullrPayment::finishProSubscription($payAmount, $token, $PayerID);

        if ($response->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus === 'ActiveProfile') {
            $recurringProfile = RecurringProfile::findOne(['userId' => \Yii::$app->user->identity->id]);

            if (isset($recurringProfile)) {
                $recurringProfile->profileId = $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                $recurringProfile->save();
            } else {
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
