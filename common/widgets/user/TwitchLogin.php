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
        $this->options['class'] = 'twitchLogin icon-twitch2';
        
        $imageOptions = [];
        $imageOptions['src'] = Application::frontendUrl('images/connect_twitch_dark.png');
        $imageTag = Html::tag('img','', $imageOptions);
        $a = Html::tag('a', $imageTag, $this->options);
        
        return $a;
    }

}