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

class SettingsController extends FrontendController {
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;
        $user->setScenario('settings');
        if ($user->load($_POST) && $user->save($_POST)) {
            $errors = UploadImage::Upload($user->id, BaseImage::TYPE_USER);
            if ($errors) {
                $user->addError('images', $errors[0]);
            } else {
                $params = ['subjectId' => $user->id, 'type' => BaseImage::TYPE_USER, 'status' => BaseImage::STATUS_APPROVED];
                $image = BaseImage::find()->where($params)->orderBy('id DESC')->one();
                if ($image && ($user->photo != $image->id)) {
                    $user->setScenario('photo');
                    $user->photo = $image->id;
                    $user->smallPhoto = $image->id;
                    $user->save();
                    $user->refresh();
                    $oldImages = BaseImage::find()->where($params)->andWhere('id < ' . $image->id)->all();
                    foreach ($oldImages as $oldImage) {
                        $oldImage->status = BaseImage::STATUS_DELETED;
                        $oldImage->save();
                    }
                }
            }
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
            $clientId = 'AdhS6hAM2klW0zvrByqMTUAwosKCt8kMrhUPN6-HHzoCaJscFJHsGfGUvLzP';
            $clientSecret = 'EHYMZhAE29WHfY8T37s-j-2wMOL8SMjjB3uX-9h9uz2snme0pL_tKYLg3YK4';
            $apiContext = new ApiContext(new OAuthTokenCredential($clientId, $clientSecret));
            define('PP_CONFIG_PATH', '/var/www/pullr/common/config/paypal');
            $payment = new Payment();
            $payment->setIntent("Sale");
            $payment->create($apiContext);
//            $plan = Plan::find($user->id);
//            $plan->prolong($_POST['subscription']);
        }

        return $this->render('index', [
                    'user' => $user,
                    'notification' => $notification,
                    'changePasswordForm' => $changePasswordForm
        ]);
    }
    
    public function actionDeactivate() {
        $deactivate = new DeactivateAccount();
        if (Yii::$app->request->isAjax) {
            $deactivate->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($deactivate);
        } else {
            /*in fact it should be true, as it was verified by ajax before sending*/
            if ($deactivate->load($_POST) && $deactivate->save()){
                $content = $this->renderPartial('@console/views/mail/deactivationEmail',[
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
