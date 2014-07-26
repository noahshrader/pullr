<?php

namespace common\models\user;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @property integer $userId
 * @property integer $systemNotificationDate
 * @property boolean $twitchPartner
 * @property string $twitchChannel
 * @property string $twitchAccessToken
 * @property integer $twitchAccessTokenDate
 * @description we used that class to store dashboard system notifications to all users
 */
class UserFields extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_user_fields';
    }
}