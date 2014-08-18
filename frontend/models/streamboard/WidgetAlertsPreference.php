<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @description One type of preference (Followers/Subscribers/Donations)
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property string $preferenceType
 * @property string $sound - filename of predefined sound
 * @property string $image - filename of predefined image
 */
class WidgetAlertsPreference extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_alerts_preference';
    }

    public function scenarios() {
        return [
            'default' => ['fontStyle', 'fontSize', 'fontColor', 'animationDuration', 'volume',
                'sound', 'image']
        ];
    }
}
