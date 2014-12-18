<?php

namespace frontend\models;

use common\models\twitch\TwitchSubscription;
use common\models\User;
use ritero\SDK\TwitchTV\TwitchSDK;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchUser;
use common\components\Application;

class TwitchHelper
{

	public static function updateFollowerNumber($user)
	{

        $channel = $user->userFields->twitchChannel;

        if ( ! $channel) {
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->channelFollows($channel, 1);
       	$data = json_decode(json_encode($data), true);
        TwitchUser::updateFollowersNumber($user, $data['_total']);
        TwitchFollow::updateFollows($user, $data['follows']);
    }

    public static function updateSubscriberNumber($user)
    {
    	$accessToken = $user->userFields->twitchAccessToken;
        $channel = $user->userFields->twitchChannel;
        $twitchPartner = $user->userFields->twitchPartner;

        if ( ! $accessToken || ! $channel || ! $twitchPartner) {
            return;
        }

        /**
         * @var TwitchSDK $twitchSDK
         */
        $twitchSDK = \Yii::$app->twitchSDK;
        $data = $twitchSDK->authChannelSubscriptions($accessToken, $channel, 1);
        /*to have array instead of object */
        $data = json_decode(json_encode($data),true);
        TwitchUser::updateSubscribersNumber($user, $data['_total']);
        TwitchSubscription::updateSubscriptions($user, $data['subscriptions']);
    }


}