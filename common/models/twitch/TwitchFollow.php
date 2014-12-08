<?php

namespace common\models\twitch;

use common\models\User;
use common\models\notifications\RecentActivityNotification;
use common\components\message\ActivityMessage;

class TwitchFollow extends TwitchFollowBase {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_twitch_follow';
    }

    public static function updateFollows($user, $follows) {
        $newFollows = [];
        if (is_array($follows) && count($follows) > 0) {
            //create notification on dashboard
            $newFollows = self::updateFollowsBase($user, $follows);
            if ($user->notification->newFollower && count($newFollows) > 0) {
                self::createNotification($user, $newFollows);
            }
        }
    }

    public static function createNotification($user, $follows) {
        foreach ($follows as $follow) {
            RecentActivityNotification::createNotification(
                $user->id,
                ActivityMessage::messageNewTwitchFollower($user, $follow['user']['name'])
            );

        }
    }

    public static function getFollowerCountByMonth($userId)
    {
        return static::getFollowCountByMonth($userId);
    }

    public static function getFollowerCountByToday($userId)
    {
        return static::getFollowCountByToday($userId);
    }

    public static function getFollowerCountByTotal($userId)
    {
        $count = \Yii::$app->db->createCommand('select followersNumber from ' . TwitchUser::tableName() . ' where userId=:id')
                        ->bindValues([
                            'id'=>$userId
                        ])
                        ->queryScalar();
        if ( ! is_numeric($count)) {
            $count = 0;
        }
        return $count;
    }
}