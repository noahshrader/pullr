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
        if (is_array($follows) && count($follows) > 0) {
            //create notification on dashboard
            if ($user->notification->newFollower) {            
                self::createNotification($user, $follows);    
            }        
            self::updateFollowsBase($user, $follows);    
        }
        
    }

    public static function createNotification($user, $follows) {        
        $follows = array_reverse($follows);
        $currentIds = static::find()->where(['userId' => $user->id])->select('twitchUserId')->orderBy('createdAt asc')->column();        
        foreach ($follows as $follow) {
            $id = $follow['user']['_id'];
            if ( ! in_array($id, $currentIds, true)) {                
                RecentActivityNotification::createNotification(
                    $user->id,
                    ActivityMessage::messageNewTwitchFollower($user, $follow['user']['name'])
                );
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