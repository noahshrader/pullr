<?php

namespace frontend\controllers;

use common\models\LoginForm;
use yii\web\Controller;
use Yii;
use common\models\OpenIDToUser;
use frontend\models\PasswordResetRequestForm;
use common\models\User;
use common\models\ChangePasswordForm;
use common\models\base\BaseImage;
use common\components\UploadImage;
/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\web\AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionSignup() {
        $model = new User();
        $model->setScenario('signup');
        if ($model->load($_POST) && $model->save()) {
            if (Yii::$app->getUser()->login($model)) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionSettings() {
        if (Yii::$app->user->isGuest){
            return Yii::$app->user->loginRequired();
        }

        
        $user = Yii::$app->user->identity;
        $user->setScenario('settings');
        if ($user->load($_POST) && $user->save($_POST)){
            $errors = UploadImage::Upload($user->id, BaseImage::TYPE_USER);
            if ($errors) {
                $user->addError('images', $errors[0]);
            } else{
                $params = ['userId' => $user->id, 'type' => BaseImage::TYPE_USER, 'status' => BaseImage::STATUS_APPROVED];
                $image = BaseImage::find()->where($params)->orderBy('id DESC')->one();
                $user->photo = $image->id;
                $user->smallPhoto = $image->id;
                $user->save();
                $user->refresh();
                $oldImages = BaseImage::find()->where($params)->andWhere('id < '.$image->id)->all();
                foreach ($oldImages as $oldImage){
                    $oldImage->status = BaseImage::STATUS_DELETED;
                    $oldImage->save();
                }
                
            }
        }
        
        $notification = $user->notification;
        $notification->load($_POST) && $notification->save($_POST);
        
        $changePasswordForm = new ChangePasswordForm();
        if ($changePasswordForm->load($_POST)){
            
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

}
