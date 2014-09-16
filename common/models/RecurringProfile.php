<?php

namespace common\models;

use yii\db\ActiveRecord;
/**
 * To associate recurring profile to user
 */
class RecurringProfile extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_recurring_profile';
    }
}
