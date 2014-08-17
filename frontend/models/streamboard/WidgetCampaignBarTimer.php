<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @description One type of preference (Followers/Subscribers/Donations)
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $countUpStatus - true - if countUp is running, in other case - false
 */
class WidgetCampaignBarTimer extends ActiveRecord {
    const TIMER_TYPE_COUNTDOWN = 'Countdown';
    const TIMER_TYPE_COUNT_UP = 'Count Up';
    const TIMER_TYPE_LOCALTIME = 'Local Time';
    static $TIMER_TYPES = [self::TIMER_TYPE_COUNTDOWN, self::TIMER_TYPE_COUNT_UP, self::TIMER_TYPE_LOCALTIME];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_campaign_bar_timer';
    }

    public function scenarios() {
        return [
            'default' => ['timerType', 'countDownFrom', 'countDownTo', 'countUpStartTime', 'countUpStatus']
        ];
    }
}
