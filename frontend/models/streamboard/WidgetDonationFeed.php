<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $scrolling
 */
class WidgetDonationFeed extends ActiveRecord {
    const SCROLL_SPEED_SLOW = 'Slow';
    const SCROLL_SPEED_NORMAL = 'Normal';
    const SCROLL_SPEED_FAST = 'Fast';
    public static $SCROLL_SPEEDS = [self::SCROLL_SPEED_SLOW, self::SCROLL_SPEED_NORMAL, self::SCROLL_SPEED_FAST];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_donation_feed';
    }

    public function scenarios() {
        return [
            'default' => ['noDonationMessage', 'fontStyle', 'fontSize', 'fontColor', 'scrolling', 'scrollSpeed']
        ];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['scrolling'] = $this->scrolling == 1;
        return $data;
    }
}
