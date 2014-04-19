<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Charity;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Event extends ActiveRecord {
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    
    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_INACTIVE];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_event';
    }
    
   /**
     * @inheritdoc
     * @return CommentQuery
     */
    public static function find()
    {
        return new query\EventQuery(get_called_class());
    }
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    /**
     * 
     * @return Charity
     */
    public function getCharity() {
        return $this->hasOne(Charity::className(), ['id' => 'charityId']);
    }
}
