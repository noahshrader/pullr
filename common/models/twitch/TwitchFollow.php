<?php

namespace common\models\twitch;

use common\models\User;

class TwitchFollow extends TwitchFollowBase {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_twitch_follow';
    }

    public static function updateFollows($userId, $follows){
        self::updateFollowsBase($userId, $follows);
    }
}