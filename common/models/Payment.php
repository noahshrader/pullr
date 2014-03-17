<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;

class Payment extends ActiveRecord {
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    
    public static $_STATUSES = [self::STATUS_APPROVED, self::STATUS_PENDING];
    
    /**
     * if payment was for pro account for month
     */
    const TYPE_PRO_MONTH = 'pro_month';
    
    /**
     * if payment was for pro account for month
     */
    const TYPE_PRO_YEAR = 'pro_year';
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_payment';
    }
    
//    public function scenarios() {
//        return [
//            'default' => ['plan','expire']
//        ];
//    }
    
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['userId' => 'id']);
    }
}
