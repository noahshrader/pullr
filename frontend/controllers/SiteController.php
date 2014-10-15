<?php

namespace frontend\controllers;

use common\components\Application;
use frontend\controllers\FrontendController;
use common\models\LoginForm;
use Yii;
use common\models\OpenIDToUser;
use common\models\User;
use common\models\twitch\TwitchUser;
use yii\web\Response;
use yii\widgets\ActiveForm;
use ritero\SDK\TwitchTV\TwitchSDK;
use common\components\FirstGivingPayment;
use frontend\models\TwitchHelper;

/**
 * Site controller
 */
class SiteController extends FrontendController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'signup', 'login', 'twitch', 'termsofservice', 'privacy', 'logout', 'fgcallback'],
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
    public function actions()
    {
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

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->actionLogin();
        } else {
            $dashboard = new DashboardController('dashboard', $this->module);
            return $dashboard->actionIndex();
        }
    }

    public function actionLogin()
    {
        //let's save user for 30 days
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

    /**
     * Login via twitch
     */
    public function actionTwitch()
    {
        if (isset($_REQUEST['error'])) {
            echo 'error: <b>' . $_REQUEST['error'] . '</b>';
            echo '<br />';
            echo "Seems pullr application's 'return url' should be changed at http://twitch.tv ";
            return;
        }
        $code = $_REQUEST['code'];

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $token = $twitchSDK->authAccessTokenGet($code);        
        $userInfo = $twitchSDK->authUserGet($token->access_token);

        // <Temp solution for beta test>
        $whitelist = file('../../whitelist', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($userInfo) && !in_array($userInfo->name, array_values($whitelist)))
        {
            $this->redirect('site/index');
            \Yii::$app->end();
        }
        // </Temp solution for beta test>
        $isNewUser = false;

        if ($userInfo) {

            $openId = OpenIDToUser::findOne(['serviceName' => 'twitch', 'serviceId' => $userInfo->_id]);
            if (!$openId) {
                $isNewUser = true;

                $user = new User();
                $user->setScenario('openId');
                $user->name = $userInfo->display_name;
                $user->uniqueName = $userInfo->name;
                $user->email = $userInfo->email;
                $user->photo = $userInfo->logo;
                $user->smallPhoto = $userInfo->logo;
                $user->save();

                $user->userFields->twitchChannel = $userInfo->name;
                $user->userFields->save();

                $openId = new OpenIDToUser();
                $openId->serviceId = $userInfo->_id;
                $openId->serviceName = 'twitch';
                $openId->url = 'http://twitch.tv/' . $userInfo->name;
                $openId->userId = $user->id;
                $openId->save();

                //update subscriber and follower                
            }
            $user = $openId->user;
            $user->userFields->twitchPartner = $userInfo->partnered;
            $user->userFields->twitchAccessToken = $token->access_token;
            $user->userFields->twitchAccessTokenDate = time();
            $user->userFields->save();

            \Yii::$app->user->login($user);          
            $this->goHome();
        } else {
            $this->redirect('/');
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionTermsofservice()
    {
        $this->layout = 'auth';
        return $this->render('termsOfService');
    }

    public function actionPrivacy()
    {
        $this->layout = 'auth';
        return $this->render('privacyPolicy');
    }

    public function actionFgcallback() {
        $request = \Yii::$app->request;

        $key = ""; $value = "";
        extract(\Yii::$app->params['firstGiving']['callbackSuccessPair']);

        if ($request->get($key) == $value) {
            $firstGivingPayment = new FirstGivingPayment();
            $firstGivingPayment->setConfig(\Yii::$app->params['firstGiving']);

            if ($firstGivingPayment->completePayment($request)) {
                die();
            }
        }
    }
}
