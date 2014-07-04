<?php
namespace common\widgets\user;

use yii\bootstrap\Widget;
use common\models\User;
use common\components\Application;
use yii\helpers\Html;
use yii\base\ErrorException;
use common\models\base\BaseImage;

class UserPhoto extends Widget{
    public $showName = false;
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
                $image = BaseImage::NO_PHOTO_LINK();
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
        
        if ($this->showName){
            $userName = Html::tag('span', '(DEV) '.\Yii::$app->user->identity->name, ['style' => 'color: red; display: inline']);
            $img = $userName.' '.$img;
        }
        
        if ($this->hasLink) {
            $this->options['href'] = $this->user->url;
            return Html::tag('a', $img, $this->options);
        } else {
            return Html::tag('span', $img, $this->options);
        }
    }
}

