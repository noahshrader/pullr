<?php

namespace common\models\twitch;

use common\models\User;
use common\models\notifications\RecentActivityNotification;
use common\components\message\ActivityMessage;
class TwitchSubscription extends TwitchFollowBase {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_twitch_subscriptions';
    }

    public static function updateSubscriptions($user, $subscriptions) {
        if (is_array($subscriptions) && count($subscriptions) > 0) {
            //create notification on dashboard
            if ($user->notification->newSubscriber) {
                self::createNotification($user, $subscriptions);    
            }        
            self::updateFollowsBase($user, $subscriptions);            
        }    
        
    }

    public static function createNotification($user, $subscriptions) {
        $subscriptions = array_reverse($subscriptions);
        $currentIds = static::find()->where(['userId' => $user->id])->select('twitchUserId')->orderBy('createdAt asc')->column();        
        foreach ($subscriptions as $subscription) {
            $id = $subscription['user']['_id'];
            if ( ! in_array($id, $currentIds)) {                
                RecentActivityNotification::createNotification(
                    $user->id,
                    ActivityMessage::messageNewTwitchSubscriber($subscription['user']['name'])
                );
            }
        }

    }

    public static function getSubscriberCountByMonth($userId)
    {               
        return static::getFollowCountByMonth($userId);
    }

    public static function getSubscriberCountByToday($userId)
    {
        return static::getFollowCountByToday($userId);
    }

    public static function getSubscriberCountByTotal($userId)
    {
        $count = \Yii::$app->db->createCommand('select subscribersNumber from ' . TwitchUser::tableName() . ' where userId=:id')
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