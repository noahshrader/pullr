<?php

namespace common\components;

use common\models\User;
use ritero\SDK\TwitchTV\TwitchSDK;

/**
 * Class Application
 * @package common\components
 * @property TwitchSDK $twitchSDK
 */
class Application extends \yii\web\Application
{

    const ID_BACKEND = 'pullr-backend';
    const ID_FRONTEND = 'pullr-frontend';

    public function __construct($config = array())
    {
        parent::__construct($config);
        if ($this->user && $this->user->id) {
            $user = $this->user->identity;
            if ($user->status != User::STATUS_ACTIVE) {
                $this->user->logout(true);
            } else {
                $oldScenario = $user->getScenario();
                $user->setScenario('last_login');
                $user->last_login = time();
                $user->save();
                $user->setScenario($oldScenario);
            }
        }
    }

    public static function IsBackend()
    {
        return \Yii::$app->id == self::ID_BACKEND;
    }

    public static function IsFrontend()
    {
        return \Yii::$app->id == self::ID_FRONTEND;
    }

    /**
     * Return string in frontend application.
     * Should be used to create links from backend to frontend
     *
     * @param String $url
     * @return String
     */
    public static function frontendUrl($url)
    {
        if (self::IsBackend() && (strpos($url, 'http') !== 0)) {
            return \Yii::$app->params['frontendUrl'] . $url;
        } else {
            return $url;
        }
    }

    public static function getBaseUrl()
    {
        $url = \Yii::$app->params['baseUrl'];
        if (strpos($url, 'http') !== 0) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    /**
     * Return string in backend application.
     * Should be used to create links from frontend to backend
     *
     * @param String $url
     * @return String
     */
    public static function backendUrl($url)
    {
        if (self::IsFrontend()) {
            return \Yii::$app->params['backendUrl'] . $url;
        } else {
            return $url;
        }
    }

    public static function IsAdmin()
    {
        return \Yii::$app->user->can('backend');
    }

    /**
     * @return User|null
     */
    public static function getCurrentUser(){
       return \Yii::$app->user->identity;
    }
    /**
     * that is a hack for rendering templates outside views
     */
    public static function render($name, $params = null, $controller = null)
    {
        $controller = new \yii\base\Controller(-1, 'hackController');
        return $controller->getView()->render($name, $params, $controller);
    }

    public function __get($name)
    {
        if ($name == 'twitchSDK'){
            return self::getTwitchSDK();
        }
        return parent::__get($name);
    }

    public function getTwitchSDK()
    {
        static $twitchSDK = null;
        if ($twitchSDK == null) {
            $twitch_config = [
                'client_id' => \Yii::$app->params['twitchClientId'],
                'client_secret' => \Yii::$app->params['twitchClientSecret'],
                'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . \Yii::$app->urlManager->baseUrl . '/app/site/twitch',
            ];
            $twitchSDK = new TwitchSDK($twitch_config);
        }
        return $twitchSDK;
    }
}
