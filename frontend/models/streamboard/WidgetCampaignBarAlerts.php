<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @description One type of preference (Followers/Subscribers/Donations)
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $includeFollowers
 * @property boolean $includeSubscribers
 * @property boolean $includeDonations
 */
class WidgetCampaignBarAlerts extends ActiveRecord {
    /*related to @web directory*/
    const PATH_TO_IMAGES = 'streamboard/alerts/images/';
    const PATH_TO_SOUNDS = 'streamboard/alerts/sounds/';

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_campaign_bar_alerts';
    }

    public function scenarios() {
        return [
            'default' => ['includeFollowers', 'includeSubscribers', 'includeDonations', 'fontStyle', 'fontSize',
                'fontColor', 'backgroundColor', 'animationDirection', 'animationDuration', 'animationDelay']
        ];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['includeFollowers'] = $this->includeFollowers == 1;
        $data['includeSubscribers'] = $this->includeSubscribers == 1;
        $data['includeDonations'] = $this->includeDonations == 1;

        return $data;
    }

    public static function PREDEFINED_IMAGES(){
        $mask = \Yii::getAlias('@app/web/'.self::PATH_TO_IMAGES.'*.{jpg,png,gif}');
        $imagesWithPath = glob($mask, GLOB_BRACE);
        $images = [];
        foreach ($imagesWithPath as $image){
           $images[] = basename($image);
        }
        return $images;
    }

    public static function PREDEFINED_SOUNDS(){
        $mask = \Yii::getAlias('@app/web/'.self::PATH_TO_SOUNDS.'*.mp3');
        $soundsWithPath = glob($mask);
        $sounds = [];
        foreach ($soundsWithPath as $sound){
            $sounds[] = basename($sound);
        }
        return $sounds;
    }
}
