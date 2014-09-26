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
     * That method sets request for account deactivation and logout user
     * If user will not login in 30 days his account will be deactivated
     */
    public function actionDeactivate()
    {
        $deactivate = new DeactivateAccount();

        if (Yii::$app->request->isAjax)
        {
            $deactivate->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($deactivate);
        }
        else
        {
            /* In fact it should be true, as it was verified by ajax before sending */
            if ($deactivate->load($_POST) && $deactivate->save())
            {
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

    /**
     * Gather user input and redirect to PayPal gateway to review PRO-subscription payment details
     */
    public function actionProstepone()
    {
        if (isset($_POST['subscription']))
        {
            $payAmount = $_POST['subscription'];
            (new Session())->set('money_amount', $payAmount);

            $setECResponse = PullrPayment::initProSubscription($payAmount);

            $payPalHost = \Yii::$app->params['payPalHost'];
            $this->redirect("$payPalHost/incontext?token={$setECResponse->Token}");
        }
    }

    /**
     * Bill user initial amount(for the 1st month\year) and subscribe to PRO recurring payment
     * Fires when user accepted payment details and ready to pay
     * @param string $token
     * @param string $PayerID
     */
    public function actionProsteptwo($token, $PayerID)
    {
        $session = new Session();
        $payAmount = $session->get("money_amount");

        $response = PullrPayment::finishProSubscription($payAmount, $token, $PayerID);

        if ($response->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus === 'ActiveProfile')
        {
            $recurringProfile = RecurringProfile::findOne(['userId' => \Yii::$app->user->identity->id]);

            if (isset($recurringProfile))
            {
                $recurringProfile->profileId = $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                $recurringProfile->save();
            }
            else
            {
                $recurringProfile = new RecurringProfile();
                $recurringProfile->profileId = $response->CreateRecurringPaymentsProfileResponseDetails->ProfileID;
                $recurringProfile->userId = \Yii::$app->user->identity->id;
                $recurringProfile->save();
            }

            $session->setFlash("pro_success", "");
            $this->redirect("index");
        }
    }

    /**
     * Deactivates PayPal recurring PRO-subscription
     */
    public function actionDeactivatepro()
    {
        $recurringProfile = RecurringProfile::findOne(['userId' => Yii::$app->user->identity->id]);

        if (isset($recurringProfile))
        {
            PullrPayment::deactivateProSubscription($recurringProfile->profileId);

            $deactivate = new DeactivatePro();
            if ($deactivate->load($_POST) && $deactivate->save())
            {
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
}
