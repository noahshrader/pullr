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

    public static function updateFollows($userId, $follows) {
        self::createNotification($userId, $follows);
        self::updateFollowsBase($userId, $follows);
    }

    public static function createNotification($userId, $follows) {
        $follows = array_reverse($follows, true);
        $ids = [];
        foreach ($follows as $key => $follow){
           $ids[] = $follow['user']['_id'];
        }
        
        $currentIds = static::find()->where(['userId' => $userId])->select('twitchUserId')->column();
        $insertIds = array_diff($ids, $currentIds);
        
        if( count($insertIds) > 0) {
            foreach ($follows as $key => $follow) {
                $id = $follow['user']['_id'];
                if (in_array($id, $insertIds)){
                    RecentActivityNotification::createNotification(
                        $userId,
                        ActivityMessage::messageNewTwitchFollower($follow['user']['name'])
                    );
                }            
            }

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
        return static::getFollowCountByTotal($userId);
    }
}