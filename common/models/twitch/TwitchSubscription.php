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

    public static function updateSubscriptions($userId, $subscriptions) {
        self::createNotification($userId, $subscriptions)
        self::updateFollowsBase($userId, $subscriptions);        
    }

    public static function createNotification($userId, $subscriptions) {
        $subscriptions = array_reverse($subscriptions, true);
        $ids = [];
        foreach ($subscriptions as $key => $subscription) {
           $ids[] = $subscription['user']['_id'];
        }

        $currentIds = static::find()->where(['userId' => $userId])->select('twitchUserId')->column();
        $insertIds = array_diff($ids, $currentIds);

        if( count($insertIds) > 0) {
            foreach ($subscriptions as $key => $subscription) {
                $id = $subscription['user']['_id'];
                if (in_array($id, $insertIds)){
                    RecentActivityNotification::createNotification(
                        $userId,
                        ActivityMessage::messageNewTwitchSubscriber($subscription['user']['name'])
                    );
                }            
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
        return static::getFollowCountByTotal($userId);
    }
}