<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * class is responsible for associating OpenID accounts to User model
 */
class Notification extends ActiveRecord {
    public static $NOTIFY_NEVER = 'never';
    
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_notification';
    }
    
    /**
     * return all notifications attributes names
     */
    public function getNotificationsAttributes(){
        return array_diff($this->attributes(), [self::$NOTIFY_NEVER, 'userId']);
    }
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
