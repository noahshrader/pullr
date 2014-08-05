<?php

namespace common\widgets\user;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use ritero\SDK\TwitchTV\TwitchSDK;
use common\components\Application;

class TwitchLogin extends Widget {

    public function run() {
        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $loginURL = $twitchSDK->authLoginURL('user_read channel_subscriptions');
        
        $this->options['href'] = $loginURL;
        $this->options['class'] = 'btn btn-default twitchLogin';
        
        $twitchTitle = '<i class="icon icon-twitch2"></i> Connect With Twitch';
        $a = Html::tag('a', $twitchTitle, $this->options);
        
        return $a;
    }

}