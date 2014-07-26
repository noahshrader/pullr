<?php
namespace frontend\controllers;

use common\models\twitch\TwitchSubscription;
use common\models\User;
use ritero\SDK\TwitchTV\TwitchSDK;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchUser;
use common\components\Application;


/**
 * Class TwitchController - responsible for storing data from twitch
 * @package frontend\controllers
 */
class TwitchController extends FrontendController {
    public function actionUpdate_follows_ajax(){
        $userId = \Yii::$app->user->id;

        $data = json_decode($_POST['data'],true);
        TwitchUser::updateFollowersNumber($userId, $data['_total']);
        if (isset($data['follows'])){
            /*jquery post doesn't sent empty arrays, so isset is needed*/
            $follows = $data['follows'];
            TwitchFollow::updateFollows($userId, $follows);
        }
    }

    public function actionUpdate_subscriptions_ajax(){
        $user = Application::getCurrentUser();
        $accessToken = $user->userFields->twitchAccessToken;
        $channel = $user->userFields->twitchChannel;
        $twitchPartner = $user->userFields->twitchPartner;

        if (!$accessToken || !$channel || !$twitchPartner){
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->authChannelSubscriptions($accessToken, $channel, 100);
        /*to have array instead of object */
        $data = json_decode(json_encode($data),true);
        TwitchUser::updateSubscribersNumber($user->id, $data['_total']);
        TwitchSubscription::updateSubscriptions($user->id, $data['subscriptions']);
    }
}
