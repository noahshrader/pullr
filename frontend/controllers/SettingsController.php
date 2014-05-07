<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\FrontendController;
use frontend\models\site\ChangePasswordForm;
use common\models\base\BaseImage;
use common\components\UploadImage;
use common\models\Plan;
use frontend\models\site\DeactivateAccount;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\mail\Mail;
use PayPal\Rest\ApiContext;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;

use common\components\PullrPayment;

class SettingsController extends FrontendController {

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;
        $user->setScenario('settings');
        if ($user->load($_POST) && $user->save($_POST)) {
            UploadImage::UploadLogo($user);
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
            $payment->proPayment($_POST['subscription']);
        }
        if (isset($_REQUEST['paymentSuccess']) && ($_REQUEST['paymentSuccess'] == 'true')){
            $payment = new PullrPayment();
            $payment->completePayment();
            $this->redirect('settings');
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
        $user = \Yii::$app->user->identity;
        if ($user->id < 10){
            $plan = Plan::findOne($user->id);
            $plan->plan = Plan::PLAN_BASE;
            $plan->expire = time();
            $plan->save();
        }
        $this->redirect('settings');
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

}
