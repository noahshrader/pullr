<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
/**
 * To store when plan were upgraded and for how much time
 */
class PlanHistory extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_plan_history';
    }
}
