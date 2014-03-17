<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;

class Notification extends ActiveRecord {
    public static $NOTIFY_NEVER = 'never';
    public static $NOTIFY_DONATION_RECEIVED = 'donationReceived';
    public static $NOTIFY_NEW_FEATURE_ADDED = 'newFeatureAdded';
    public static $NOTIFY_NEW_THEME_AVAILABLE= 'newThemeAvailable';
    public static $NOTIFY_SYSTEM_UPDATE = 'systemUpdate';
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_notification';
    }
    public function scenarios() {
        return [
            'default' => [self::$NOTIFY_NEVER, self::$NOTIFY_DONATION_RECEIVED, self::$NOTIFY_NEW_FEATURE_ADDED, self::$NOTIFY_NEW_THEME_AVAILABLE, self::$NOTIFY_SYSTEM_UPDATE]
        ];
    }
    public function attributeLabels() {
        return [
            self::$NOTIFY_NEVER => 'Never send me emails',
            self::$NOTIFY_DONATION_RECEIVED => 'When I receive a donation',
            self::$NOTIFY_NEW_FEATURE_ADDED => 'When new features are added',
            self::$NOTIFY_NEW_THEME_AVAILABLE => 'When new themes are available',
            self::$NOTIFY_SYSTEM_UPDATE => 'System updates'
        ];
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
