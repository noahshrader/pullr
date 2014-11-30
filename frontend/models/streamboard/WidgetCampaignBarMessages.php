<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @description One type of preference (Followers/Subscribers/Donations)
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 */
class WidgetCampaignBarMessages extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_campaign_bar_messages';
    }

    public function scenarios() {
        return [
            'default' => ['message1','message2', 'message3', 'message4', 'message5', 'rotationSpeed', 'positionX', 'positionY']
        ];
    }
}
