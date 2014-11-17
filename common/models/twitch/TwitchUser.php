<?php

namespace common\models\twitch;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @description That class is used to store twitch follower's number and subscriber's number for user
 * @property integer $userId
 * @property integer $followersNumber
 * @property integer $subscribersNumber
 * @property integer $updateDate - timestamp of last update
 */
class TwitchUser extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_twitch_user';
    }

    private static function updateField($userId, $field, $value){
        $twitchUser = TwitchUser::findOne($userId);
        if (!$twitchUser){
            $twitchUser = new TwitchUser();
            $twitchUser->userId = $userId;
        }

        $twitchUser->$field = $value;
        $twitchUser->updateDate = time();
        $twitchUser->save();      
    }

    public static function updateFollowersNumber($user, $followersNumber){
        self::updateField($user->id,'followersNumber', $followersNumber);
    }

    public static function updateSubscribersNumber($user, $subscribersNumber){
        self::updateField($user->id,'subscribersNumber', $subscribersNumber);
    }
}