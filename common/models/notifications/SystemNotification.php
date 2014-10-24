<?php

namespace common\models\notifications;

use yii\db\ActiveRecord;
use common\models\Campaign;
use common\models\User;
use common\models\user\UserFields;

/**
 * we used these class to create dashboard system notifications to all users
 */
class SystemNotification extends ActiveRecord {
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const DATE_FORMAT = 'M j Y h:iA';

    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_DELETED];
    
    public function init() {
        parent::init();
        $this->date = time()+1;
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_notification_system';
    }
    
    public static function getNotificationForUser($userId){
        $time = UserFields::findOne($userId)->systemNotificationDate;
        //return SystemNotification::find(['userId' => $userId])->andWhere('date > '.$time)->orderBy('date DESC')->one();
        return SystemNotification::findOne(["status" => "active"]);
    }
    
    public static function readNotificationForUser($userId, $notificationId){
        $notification = SystemNotification::findOne($notificationId);
        $userFields = UserFields::findOne($userId);
        $userFields->systemNotificationDate = $notification->date + 1;
        $userFields->save();
    }

    public function scenarios() {
        return [
            'default' => ['message', 'status', 'date'],
        ];
    }

    public function rules() {
        return [
            ['message', 'required'],
            ['message', 'filter', 'filter' => 'strip_tags'],
            ['status', 'in', 'range' => self::$STATUSES],
            ['date', 'required'],
            ['date', 'filter', 'filter' => 'strip_tags'],
            ['date', 'required'],
        ];
    }
}