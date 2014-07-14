<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * Class StreamboardCampaigns Is used to store selected campaigns by user.
 * @package frontend\models\streamboard
 * @property boolean $selected
 */
class StreamboardCampaign extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_campaign';
    }
}
