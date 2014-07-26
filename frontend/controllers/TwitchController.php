<?php
namespace frontend\controllers;

use common\models\User;
use ritero\SDK\TwitchTV\TwitchSDK;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchUser;


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
            foreach ($follows as $key => $value){
               $follow = &$follows[$key]['user'];
               $follow['name'] = strip_tags($follow['name']);
               $follow['display_name'] = strip_tags($follow['display_name']);
            }
            TwitchFollow::updateFollows($userId, $follows);
        }
    }

    public function actionUpdate_subscriptions_ajax(){
        /**
         * @var User $user
         */
        $user = \Yii::$app->user->identity;

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
        $array = $twitchSDK->authChannelSubscriptions($accessToken, $channel);
        var_dump($array);
        return;
    }
}
