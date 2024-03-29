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
        $newSubscribers = [];
        if (is_array($subscriptions) && count($subscriptions) > 0) {

            $newSubscribers = self::updateFollowsBase($user, $subscriptions);
            //create notification on dashboard
            if ($user->notification->newSubscriber && count($newSubscribers) > 0) {
                self::createNotification($user, $newSubscribers);
            }
        }

    }

    public static function createNotification($user, $subscriptions) {
        $subscriptions = array_reverse($subscriptions);

        foreach ($subscriptions as $subscription) {
            RecentActivityNotification::createNotification(
                $user->id,
                ActivityMessage::messageNewTwitchSubscriber($user, $subscription['user']['name'])
            );

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