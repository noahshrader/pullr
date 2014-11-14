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
 * @property float $volume from 0 to 1
 * @property string $image - filename of predefined image
 * @property string $imageType - FILE_TYPE_LIBRARY | FILE_TYPE_CUSTOM
 */
class WidgetAlertsPreference extends ActiveRecord {
    const FILE_TYPE_LIBRARY = 'Library';
    const FILE_TYPE_CUSTOM = 'Custom';
    public static $ANIMATION_STYLE = [
        'Bounce In'         => ['bounceIn', 'bounceOut'],
        'Bounce Down'       => ['bounceInUp','bounceOutUp'],
        'Bounce Up'         => ['bounceInDown','bounceOutDown'],
        'Bounce Left'       => ['bounceInLeft','bounceOutLeft'],
        'Bounce Right'      => ['bounceInRight','bounceOutRight'],
        'Fade'              => ['fadeIn','fadeOut'],
        'Flip Vertical'     => ['flipInX','flipOutX'],
        'Flip Horizontal'   => ['flipInY','flipOutY'],
        'Slide Down'        => ['slideInDown','slideOutUp'],
        'Slide Up'          => ['slideInUp','slideOutDown'],
        'Slide Left'        => ['slideInLeft','slideOutLeft'],
        'Slide Right'       => ['slideInRight', 'slideOutRight']
    ];
    public static $FILE_TYPES = [self::FILE_TYPE_CUSTOM, self::FILE_TYPE_LIBRARY];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_alerts_preference';
    }

    public function scenarios() {
        return [
            'default' => ['alertText', 'fontStyle', 'fontSize', 'fontWeight', 'fontColor', 'animationDuration', 'volume',
                'sound','soundType', 'image', 'imageType', 'hideAlertText', 'hideAlertImage']
        ];
    }

    public function beforeValidate() {
        if ($this->isNewRecord) {
            $this->fontColor = '#FFFFFF';
        }
        return parent::beforeValidate();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['hideAlertText'] = $this->hideAlertText == 1;
        $data['hideAlertImage'] = $this->hideAlertImage == 1;
        return $data;
    }
}
