<?php

namespace common\models\twitch;

use yii\db\ActiveRecord;
use common\models\User;
use yii\db\Query;
use yii\db\QueryBuilder;

/**
 * @property integer $userId
 * @property integer $twitchUserId - follower's id from Twitch
 * @property integer $createdAt - date when follower started to follow [user]
 * @property string $name - unique name from Twitch
 * @property string $display_name - display_name from Twitch
 * @property string $jsonResponse - all json data from twitch api for that "follow"
 * @property string $updateDate - last time when record was updated
 * @property string $createdAtPullr - time when record was inserted at pullr, that field is used to push notifications at insert
 * @description That class is used to store twitch followers for user
 */
abstract class TwitchFollowBase extends ActiveRecord {
    protected static function updateFollowsBase($user, $follows)
    {
        $follows = array_reverse($follows);
        $userId = $user->id;
        $newFollows = [];
        $connection = \Yii::$app->db;
        foreach ($follows as $key => $follow){
           $follows[$key]['created_at'] = strtotime($follow['created_at']);
        }
        $currentIds = static::find()->where(['userId' => $userId])->select('twitchUserId')->column();
        $maxCreatedAt = static::find()->select('max(createdAt)')->scalar();

        if ($maxCreatedAt === false) {
            $maxCreatedAt = 0;
        }
        $fields = ['userId', 'twitchUserId', 'createdAt', 'name', 'display_name', 'jsonResponse', 'updateDate', 'createdAtPullr'];
        $rows = [];
        $insertIds = [];
        foreach ($follows as $follow) {
            $id = $follow['user']['_id'];
            if ($follow['created_at'] > $maxCreatedAt) {
                $row = [$userId, $id, $follow['created_at'], $follow['user']['name'],$follow['user']['display_name'], json_encode($follow), time(), time()];
                $rows[] = $row;
                $newFollows[] = $follow;
                $insertIds[] = $id;
            }
        }

        if (count($rows) > 0) {
            //prevent conflict with old record
            $connection->createCommand()->delete(static::tableName(), ['in', 'twitchUserId', $insertIds])->execute();

            $connection->createCommand()->batchInsert(static::tableName(), $fields, $rows)->execute();
        }

        return $newFollows;


    }

    public static function getFollowCountByMonth($userId)
    {
        $count = \Yii::$app->db->createCommand('select count(*) from ' . static::tableName() . ' where userId=:id and DATE_FORMAT(FROM_UNIXTIME(createdAt), "%m-%Y") = DATE_FORMAT(NOW(), "%m-%Y")')
                        ->bindValues([
                            'id'=>$userId
                        ])
                        ->queryScalar();
        return $count;
    }

    public static function getFollowCountByToday($userId)
    {
        $count = \Yii::$app->db->createCommand('select count(*) from ' . static::tableName() . ' where userId=:id and DATE_FORMAT(FROM_UNIXTIME(createdAt), "%d-%m-%Y") = DATE_FORMAT(NOW(), "%d-%m-%Y")')
                        ->bindValues([
                            'id'=>$userId
                        ])
                        ->queryScalar();
        return $count;
    }

}