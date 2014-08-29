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
 * @property string $fontSize
 * @property string $fontColor
 * @property string $fontStyle
 * @property string $animationDuration
 * @property string $sound - filename of predefined sound
 * @property string $soundType - FILE_TYPE_LIBRARY | FILE_TYPE_CUSTOM
 * @property string $image - filename of predefined image
 * @property string $imageType - FILE_TYPE_LIBRARY | FILE_TYPE_CUSTOM
 */
class WidgetAlertsPreference extends ActiveRecord {
    const FILE_TYPE_LIBRARY = 'Library';
    const FILE_TYPE_CUSTOM = 'Custom';
    public static $FILE_TYPES = [self::FILE_TYPE_CUSTOM, self::FILE_TYPE_LIBRARY];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_alerts_preference';
    }

    public function scenarios() {
        return [
            'default' => ['fontStyle', 'fontSize', 'fontColor', 'animationDuration', 'volume',
                'sound','soundType', 'image', 'imageType']
        ];
    }
}
