<?php

namespace common\models;

use yii\db\ActiveRecord;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Theme extends ActiveRecord {
    const STATUS_ACTIVE = 'active';
    const STATUS_REMOVED = 'removed';
    const PLAN_PRO = 'Pro';
    const PLAN_BASIC = 'Basic';
    
    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_REMOVED];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_theme';
    }
}
