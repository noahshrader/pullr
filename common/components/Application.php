<?php

namespace common\components;

class Application extends \yii\web\Application{

    const ID_BACKEND = 'pullr-backend';
    const ID_FRONTEND = 'pullr-frontend';
    
    public function __construct($config = array()) {
        parent::__construct($config);
        if ($this->user && $this->user->id){
            $user = $this->user->identity;
            $oldScenario = $user->getScenario();
            $user->setScenario('last_login');
            $user->last_login = time();
            $user->save();
            $user->setScenario($oldScenario);
        }
    }
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
        if (self::IsBackend()){
            return \Yii::$app->params['frontendUrl'] . $url;
        } else {
            return $url;
        }
    }
    
    /**
     * Return string in backend application. 
     * Should be used to create links from frontend to backend
     * 
     * @param String $url
     * @return String
     */
    public static function backendUrl($url) {
        if (self::IsFrontend()){
            return \Yii::$app->params['backendUrl'] . $url;
        } else {
            return $url;
        }
    }
    
    public static function IsAdmin(){
        return \Yii::$app->user->checkAccess('backend');
    }
}
