<?php

namespace common\models\user;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * we used that class to store dashboard system notifications to all users
 */
class UserFields extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_user_fields';
    }
}