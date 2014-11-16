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
        self::createNotification($userId, $subscriptions);
        self::updateFollowsBase($userId, $subscriptions);        
    }

    public static function createNotification($userId, $subscriptions) {

        $currentIds = static::find()->where(['userId' => $userId])->select('twitchUserId')->orderBy('createdAt desc')->column();        
        foreach ($subscriptions as $subscription) {
            $id = $subscription['user']['_id'];
            if ( ! in_array($id, $currentIds)) {                
                RecentActivityNotification::createNotification(
                    $userId,
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