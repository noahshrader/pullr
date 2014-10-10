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
	public static function updateFollows()
	{
        $user = Application::getCurrentUser();
        if ($user) {
	        $channel = $user->userFields->twitchChannel;
	        $twitchPartner = $user->userFields->twitchPartner;

	        if ( !$channel || !$twitchPartner){
	            return;
	        }

	        /**
	         * @var TwitchSDK $twitchSDK
	         */
	        $twitchSDK = \Yii::$app->twitchSDK;
	        $data = $twitchSDK->channelFollows($channel, 100);
	       	$data = json_decode(json_encode($data), true);
	        TwitchUser::updateFollowersNumber($user->id, $data['_total']);	       
	        TwitchFollow::updateFollows($user->id, $data['follows']);	
        }              
    }

    public static function updateSubscriptions()
    {
        $user = Application::getCurrentUser();
        if ($user) {
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
}