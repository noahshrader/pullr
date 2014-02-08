<?php
namespace common\widgets\user;

use yii\bootstrap\Widget;
use common\models\User;
use common\components\Application;
use yii\helpers\Html;
use yii\base\ErrorException;

class UserPhoto extends Widget{
    /**
     *
     * @var User
     */
    public $user;
    /**
     *
     * @var String shoud be either smallphoto or photo 
     */
    public $type = 'smallPhoto';
    public $hasLink = true;
    public function run(){
        Html::addCssClass($this->options, 'user-photo');
        $type = $this->type;
        $imgOptions = [];
        if ($type == 'smallPhoto' || $type == 'photo'){
            Html::addCssClass($imgOptions, $type);
            if ($type=='photo'){
                Html::addCssClass($imgOptions, 'img-responsive');
            }
            $image = $this->user->$type;
            if (!$image){
                $image = 'images/no_photo.png';
                if (Application::IsBackend()){
                    $image = Application::frontendUrl($image);
                }
                Html::addCssClass($imgOptions, 'no-photo');
            }
            $imgOptions['src'] = $image;
        } else {
            throw new ErrorException('UserPhoto type should be either smallPhoto || photo');
        }
        
        $img = Html::tag('img', '', $imgOptions);
        
        if ($this->hasLink) {
            $this->options['href'] = $this->user->url;
            return Html::tag('a', $img, $this->options);
        } else {
            return Html::tag('span', $img, $this->options);
        }
    }
}

