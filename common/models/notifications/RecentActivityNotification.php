<?php

namespace common\models\notifications;

use yii\db\ActiveRecord;
use common\models\Campaign;
use common\models\User;

/**
 * we used these class to create recent activity notifications for user, 
 * which showed at dashboard
 */
class RecentActivityNotification extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_notification_recent_activity';
    }
    
    public function init() {
        parent::init();
        $this->date = time();
    }
}