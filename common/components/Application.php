<?php

namespace common\components;

class Application {

    const ID_BACKEND = 'pullr-backend';
    const ID_FRONTEND = 'pullr-frontend';

    public static function IsBackend() {
        return \Yii::$app->id == self::ID_BACKEND;
    }
    
    public static function IsFrontend() {
        return \Yii::$app->id == self::ID_FRONTEND;
    }

    /**
     * Return string in frontend application. 
     * Should be used to create links from backend to frontend
     * 
     * @param String $url
     * @return String
     */
    public static function frontendUrl($url) {
        return \Yii::$app->params['frontendUrl'] . $url;
    }
    
    /**
     * Return string in backend application. 
     * Should be used to create links from frontend to backend
     * 
     * @param String $url
     * @return String
     */
    public static function backendUrl($url) {
        return \Yii::$app->params['backendUrl'] . $url;
    }
    
    public static function IsAdmin(){
        return \Yii::$app->user->checkAccess('backend');
    }
}
