<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $scrolling
 * @property boolean $showFollower
 * @property boolean $showSubscriber
 */
class WidgetDonationFeed extends ActiveRecord {
    const SCROLL_SPEED_SLOW = 'Slow';
    const SCROLL_SPEED_NORMAL = 'Normal';
    const SCROLL_SPEED_FAST = 'Fast';

    public static $SCROLL_SPEEDS = [
        self::SCROLL_SPEED_SLOW,
        self::SCROLL_SPEED_NORMAL,
        self::SCROLL_SPEED_FAST
    ];

    const SORT_BY_AMOUNT = 'Amount';
    const SORT_BY_TIME = 'Time';
    const SORT_BY_ALPHABET = 'Alphabet';

    public static $SORT_TYPE = [
        self::SORT_BY_AMOUNT,
        self::SORT_BY_TIME,
        self::SORT_BY_ALPHABET
    ];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_donation_feed';
    }

    public function scenarios() {
        return [

            'default' => ['noDonationMessage', 'fontStyle', 'fontSize', 'fontColor', 'fontWeight', 'fontUppercase', 'scrolling', 'scrollSpeed',
            'positionX', 'positionY', 'width', 'height','textShadow','groupBase', 'sortBy']
        ];
    }

    public function beforeValidate() {
        if ($this->isNewRecord) {
            $this->fontColor = '#FFFFFF';
            $this->sortBy = self::SORT_BY_AMOUNT;
        }
        return parent::beforeValidate();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true) {
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['scrolling'] = $this->scrolling == 1;
        $data['showSubscriber'] = $this->showSubscriber == 1;
        $data['showFollower'] = $this->showFollower == 1;
        $data['groupUser'] = $this->groupUser == 1;
        $data['fontUppercase'] = $this->fontUppercase == 1;
        $data['groupBase'] = $this->groupBase?$this->groupBase:'name';
        $data['textShadow'] = $this->textShadow == 1;
        return $data;
    }

}
