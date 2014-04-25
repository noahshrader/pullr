<?php

namespace common\components;

use Yii;

class PhpManager extends \yii\rbac\PhpManager
{
    public $authFile = '@common/components/rbac.php';

    public function init()
    {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            // we suppose that user's role is stored in identity
            $role = $this->getRole(Yii::$app->user->identity->role);
            $this->assign($role, Yii::$app->user->identity->id);
            
        }
    }
    
    /*just to not save rbac into persistent storage*/
    public function save(){
        
    }
}
