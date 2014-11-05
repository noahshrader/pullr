<?php

namespace frontend\controllers;

use frontend\models\site\ChangePasswordForm;
use frontend\models\site\DeactivateAccount;
use frontend\models\site\DeactivatePro;
use common\models\RecurringProfile;
use common\components\PullrPayment;
use common\models\mail\Mail;
use \common\components\PullrUtils;
use yii\widgets\ActiveForm;
use \yii\web\Session;
use yii\web\Response;
use Yii;

class SettingsController extends FrontendController 
{
    public function actionIndex() 
    {
        if (Yii::$app->user->isGuest) 
        {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;
        $user->setScenario('settings');

        if (isset($_POST['User']))
        {
            $user->setAttributes($_POST['User']);
            $user->save();
        }

        $notification = $user->notification;
        
        $notification->load($_POST) && $notification->save($_POST);

        $changePasswordForm = new ChangePasswordForm();
        if ($changePasswordForm->load($_POST) && $changePasswordForm->oldPassword) 
        {
            $changePasswordForm->validatePassword();
            $changePasswordForm->validateNewPassword();
            if (!$changePasswordForm->getErrors()) 
            {
                $user->setNewPassword($changePasswordForm->newPassword);
                $user->save();
                $changePasswordForm = new ChangePasswordForm();
                $changePasswordForm->success = true;
            }
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

            $apiConfig = $apiConfig = \Yii::$app->params['payPal'];
            $payPalResponse = (new PullrPayment($apiConfig))->initProSubscription($payAmount);

            $payPalHost = \Yii::$app->params['payPalHost'];
            $this->redirect("$payPalHost/incontext?token={$payPalResponse->Token}");
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

        try {
            $apiConfig = $apiConfig = \Yii::$app->params['payPal'];
            $response = (new PullrPayment($apiConfig))->finishProSubscription($payAmount, $token, $PayerID);

            if (isset($response) && ($response->CreateRecurringPaymentsProfileResponseDetails->ProfileStatus === 'ActiveProfile'))
            {
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
            }
            $session->setFlash("pro_success", "");
        }
        catch (\Exception $ex) {
            $session->setFlash("pro_failure", "");
        }

        $this->redirect("index");
    }

    /**
     * Deactivates PayPal recurring PRO-subscription
     */
    public function actionDeactivatepro()
    {
        $recurringProfile = RecurringProfile::findOne(['userId' => Yii::$app->user->identity->id]);

        if (isset($recurringProfile))
        {
            $apiConfig = $apiConfig = \Yii::$app->params['payPal'];
            (new PullrPayment($apiConfig))->deactivateProSubscription($recurringProfile->profileId);

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
    
    public  function actionDetecttimezone($offset, $dst, $multiple = false, $default = 'UTC')
    {
        $timezone_ids = array();

        // Get the timezone list
        $timezones = PullrUtils::timezone_list();

        // Try to find a timezone for which both the offset and dst match
        foreach ($timezones as $timezone_id)
        {
            $timezone_data = PullrUtils::get_timezone_data($timezone_id);
            if ($timezone_data['offset'] == $offset && $dst == $timezone_data['dst'])
            {
                array_push($timezone_ids, $timezone_id);
                if ( ! $multiple)
                    break;
            }
        }

        if (empty($timezone_ids))
        {
            $timezone_ids = array($default);
        }
        
        $timezone = $timezone_ids[0];
        //update user timezone
        $user = Yii::$app->user->identity;
        $user->timezone = $timezone;
        $user->save(false);
        
        return $timezone;
    }
}