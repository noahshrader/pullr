<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * Class StreamboardConfig is used to store user's settings for streamboard.
 * @package frontend\models\streamboard
 */
class StreamboardConfig extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_config';
    }


}
