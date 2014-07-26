<?php

namespace common\models\twitch;

use common\models\User;

class TwitchSubscription extends TwitchFollowBase {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_twitch_subscriptions';
    }

    public static function updateSubscriptions($userId, $subscriptions){
        self::updateFollowsBase($userId, $subscriptions);
    }
}