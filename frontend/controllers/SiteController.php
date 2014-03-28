<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\LoginForm;
use Yii;
use common\models\OpenIDToUser;
use frontend\models\PasswordResetRequestForm;
use common\models\User;
use frontend\models\site\ChangePasswordForm;
use common\models\base\BaseImage;
use common\components\UploadImage;
use common\models\Plan;
use common\models\mail\Mail;
use frontend\models\site\DeactivateAccount;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\models\site\EmailConfirmation;

/**
 * Site controller
 */
class SiteController extends FrontendController{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\web\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'signup', 'login', 'termsofservice', 'privacypolicy', 'logout', 'resendemailconfirmation', 'confirmemail'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['frontend'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLogin() {
        /*         * let's save user for 30 days */
        $duration = 3600 * 24 * 30;
        $serviceName = Yii::$app->getRequest()->get('service');

        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->getComponent('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
                    //                  echo json_encode($eauth->getAttributes()); exit;

                    $identity = OpenIDToUser::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity, $duration);
                    // special redirect with closing popup window
                    $eauth->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());

                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
            //that code should never be reached
            return;
        }

        // default authorization code through login/password .
        $model = new LoginForm();
        if ($model->load($_POST) && $model->login($duration)) {
            return $this->goBack();
        }

        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionConfirmemail(){
        $email = $_REQUEST['email'];
        $key = $_REQUEST['key'];
        $confirmation = EmailConfirmation::find()->where(['status' => EmailConfirmation::STATUS_SENT, 'key' => $key, 'email' => $email ])->orderBy('lastSent DESC')->one();
        
        if ($confirmation){
            $user = $confirmation->user;
            $user->setScenario('emailConfirm');
            if ($user->role == User::ROLE_ONCONFIRMATION){
                $user->role = User::ROLE_USER;
            }
            $user->email = $user->email;
            $user->save();
            $confirmation->status = EmailConfirmation::STATUS_APPROVED;
            $confirmation->save();
            return $this->render('emailWasConfirmed');
        }
        
        return $this->goHome();
    }
    
    
    public function sendConfirmEmail($email, $key){
        $content = $this->renderPartial('@console/views/mail/confirmationEmail',[
            'email' => $email,
            'key' => $key
        ]);
        Mail::sendMail($email, 'Confirm email', $content, 'emailConfirm');
    }
    
    public function sendConfirmEmailFirst($user) {
        $email = $user->login;
        $key = EmailConfirmation::getKeyForEmail($email, true);
        $this->sendConfirmEmail($email, $key);
    }
    public function actionResendemailconfirmation(){
        $confirmation = EmailConfirmation::find()->where(['status' => EmailConfirmation::STATUS_SENT, 'userId' => \Yii::$app->user->id])->orderBy('lastSent DESC')->one();
        if ($confirmation && (time() - $confirmation->lastSent > 60)){
            $key = EmailConfirmation::getKeyForEmail($confirmation->email, true);
            $this->sendConfirmEmail($confirmation->email, $key);
        }
    }
    public function actionSignup() {
        $user = new User();
        $user->setScenario('signup');
        if (Yii::$app->request->isAjax) {
            $user->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        /*in fact it should be true, as it was verified by ajax before sending*/
        if ($user->load($_POST)) {
            $user->role = User::ROLE_ONCONFIRMATION;
            if ($user->save() && Yii::$app->getUser()->login($user)) {
                $this->sendConfirmEmailFirst($user);
                return $this->goHome();
            }
        }
        return $this->goHome();
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
                Yii::$app->getUser()->logout(true);
                $this->redirect('/');
            }
        }
    }

    public function actionSettings() {
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
            $plan = Plan::find($user->id);
            $plan->prolong($_POST['subscription']);
        }

        return $this->render('settings', [
                    'user' => $user,
                    'notification' => $notification,
                    'changePasswordForm' => $changePasswordForm
        ]);
    }

    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load($_POST) && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }
    
    public function actionTermsofservice(){
        return $this->render('termsOfService');
    }
    
    public function actionPrivacypolicy(){
        return $this->render('privacyPolicy');
    }
    
}
