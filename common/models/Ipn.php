<?php

namespace common\models;

use yii\db\ActiveRecord;
/**
 * To store Instant Payment Notifications from PayPal
 */
class Ipn extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_ipn';
    }
}
