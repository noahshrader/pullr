<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\LoginForm;
use Yii;
use common\models\OpenIDToUser;
use frontend\models\PasswordResetRequestForm;
use common\models\User;
use common\models\mail\Mail;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\models\site\EmailConfirmation;
use ritero\SDK\TwitchTV\TwitchSDK;
use frontend\models\ResetPasswordForm;
/**
 * Site controller
 */
class SiteController extends FrontendController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'signup', 'login', 'twitch', 'termsofservice', 'privacypolicy', 'logout', 'resendemailconfirmation', 'confirmemail', 'requestpasswordreset', 'resetpassword'],
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
//        /*         * let's save user for 30 days */
        $duration = 3600 * 24 * 30;
//        $serviceName = Yii::$app->getRequest()->get('service');
//
//        if (isset($serviceName)) {
//            /** @var $eauth \nodge\eauth\ServiceBase */
//            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
//            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
//            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
//            try {
//                if ($eauth->authenticate()) {
//                    //                  echo json_encode($eauth->getAttributes()); exit;
//
//                    $identity = OpenIDToUser::findByEAuth($eauth);
//                    Yii::$app->getUser()->login($identity, $duration);
//                    // special redirect with closing popup window
//                    $eauth->redirect();
//                } else {
//                    // close popup window and redirect to cancelUrl
//                    $eauth->cancel();
//                }
//            } catch (\nodge\eauth\ErrorException $e) {
//                // save error to show it later
//                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
//
//                // close popup window and redirect to cancelUrl
////              $eauth->cancel();
//                $eauth->redirect($eauth->getCancelUrl());
//            }
//            //that code should never be reached
//            return;
//        }
        // default authorization code through login/password .
        $model = new LoginForm();
        if (Yii::$app->request->isAjax) {
            $model->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($_POST) && $model->login($duration)) {
            return $this->goBack();
        }

        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /* login via twitch */

    public function actionTwitch() {
        if (isset($_REQUEST['error'])){
            echo 'error: <b>'.$_REQUEST['error'].'</b>';
            echo '<br />';
            echo "Possible pullr application return url should be changed at http://twitch.tv ";
            return;
        }
        $code = $_REQUEST['code'];
        $twitch_config = [
            'client_id' => \Yii::$app->params['twitchClientId'],
            'client_secret' => \Yii::$app->params['twitchClientSecret'],
            'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . \Yii::$app->urlManager->baseUrl . '/app/site/twitch',
        ];
        $twitch = new TwitchSDK($twitch_config);
        
        $token =  $twitch->authAccessTokenGet($code);
        $userInfo = $twitch->authUserGet($token->access_token);
        
        if ($userInfo){
        
            $openId = OpenIDToUser::findOne(['serviceName' => 'twitch', 'serviceId' => $userInfo->_id ]);
            if (!$openId){
                $user = new User();
                $user->setScenario('openId');
                $user->name = $userInfo->display_name;
                $user->uniqueName = $userInfo->name;
                $user->email = $userInfo->email;
                $user->photo = $userInfo->logo;
                $user->smallPhoto = $userInfo->logo;
                $user->save();
                $openId = new OpenIDToUser();
                $openId->serviceId = $userInfo->_id;
                $openId->serviceName = 'twitch';
                $openId->url = 'http://twitch.tv/'.$userInfo->name;
                $openId->userId = $user->id;
                $openId->save();
            }
            $user = $openId->user;
            \Yii::$app->user->login($user);
            
            $this->goHome();
        } else {
            $this->redirect('/');
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionConfirmemail() {
        $email = $_REQUEST['email'];
        $key = $_REQUEST['key'];
        $confirmation = EmailConfirmation::find()->where(['status' => EmailConfirmation::STATUS_SENT, 'key' => $key, 'email' => $email])->orderBy('lastSent DESC')->one();

        if ($confirmation) {
            $user = $confirmation->user;
            $user->setScenario('emailConfirm');
            if ($user->role == User::ROLE_ONCONFIRMATION) {
                $user->role = User::ROLE_USER;
            }
            $user->email = $user->login;
            $user->save();
            $confirmation->status = EmailConfirmation::STATUS_APPROVED;
            $confirmation->save();
            return $this->render('emailWasConfirmed');
        }

        return $this->goHome();
    }

    public function sendConfirmEmail($email, $key) {
        $content = $this->renderPartial('@console/views/mail/confirmationEmail', [
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

    public function actionResendemailconfirmation() {
        $confirmation = EmailConfirmation::find()->where(['status' => EmailConfirmation::STATUS_SENT, 'userId' => \Yii::$app->user->id])->orderBy('lastSent DESC')->one();
        if ($confirmation && (time() - $confirmation->lastSent > 60)) {
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

        /* in fact it should be true, as it was verified by ajax before sending */
        if ($user->load($_POST)) {
            $user->role = User::ROLE_ONCONFIRMATION;
            if ($user->save() && Yii::$app->getUser()->login($user)) {
                $this->sendConfirmEmailFirst($user);
                return $this->goHome();
            }
        }
        return $this->goHome();
    }

    public function actionRequestpasswordreset() {
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

    public function actionResetpassword($token) {
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

    public function actionTermsofservice() {
        return $this->render('termsOfService');
    }

    public function actionPrivacypolicy() {
        return $this->render('privacyPolicy');
    }

}
