<?php

namespace common\models\base;


class EmptyImage implements IBaseImage {
    private static $instance = null;
    
    private function __construct(){

    }
    
    public static function get(){
        if (!self::$instance){
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getMiddleUrl() {
        return 'images/no_photo.png';
    }

    public function getOriginalUrl() {
        return $this->getMiddleUrl();
    }
    
    public function getId(){
        return 0;
    }
    
    public function getUserId(){
        return 0;
    }
    
    public function __get($name){
        $methodName = 'get'.ucfirst($name);
        if (method_exists($this, $methodName)){
            return $this->$methodName();
        }
        trigger_error("Field $name doesn't exist");
    }
}

