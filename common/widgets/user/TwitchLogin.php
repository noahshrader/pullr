<?php

namespace common\widgets\user;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use ritero\SDK\TwitchTV\TwitchSDK;

class TwitchLogin extends Widget {

    public function run() {
        
        $twitch_config = [
            'client_id' => \Yii::$app->params['twitchClientId'],
            'client_secret' => \Yii::$app->params['twitchClientSecret'],
            'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . \Yii::$app->urlManager->baseUrl . '/app/site/twitch',
        ];
        $twitch = new TwitchSDK($twitch_config);
        $loginURL = $twitch->authLoginURL('user_read');
        
        $this->options['href'] = $loginURL;
        $this->options['class'] = 'twitchLogin';
        
        $imageOptions = [];
        $imageOptions['src'] = \common\components\Application::frontendUrl('images/connect_twitch_dark.png');
        $imageTag = Html::tag('img','', $imageOptions);
        $a = Html::tag('a', $imageTag, $this->options);
        
        return $a;
    }

}
