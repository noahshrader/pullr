<?php
namespace common\widgets\user;

use yii\bootstrap\Widget;
use common\models\User;
use yii\helpers\Html;

class UserLink extends Widget{
    /**
     *
     * @var User
     */
    public $user;
    
    public function run(){
        $this->options['href'] = $this->user->url;
        return Html::tag('a', $this->user->name, $this->options);
    }
}
