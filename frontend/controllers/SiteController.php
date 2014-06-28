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
                        'actions' => ['index', 'signup', 'login', 'twitch', 'termsofservice', 'privacy', 'logout'],
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
        if (\Yii::$app->user->isGuest){
            return $this->actionLogin();
        } else {
            return $this->render('index');
        }
    }

    public function actionLogin() {
//        /*         * let's save user for 30 days */
        $duration = 3600 * 24 * 30;
        $model = new LoginForm();
        if (Yii::$app->request->isAjax) {
            $model->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($_POST) && $model->login($duration)) {
            return $this->goBack();
        }
        
        $this->layout = 'auth';
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

    public function actionTermsofservice() {
        $this->layout = 'auth';
        return $this->render('termsOfService');
    }

    public function actionPrivacy() {
        $this->layout = 'auth';
        return $this->render('privacyPolicy');
    }

}
