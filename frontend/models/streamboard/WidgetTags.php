<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;


/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $lastFollower
 * @property boolean $lastSubscriber
 * @property boolean $lastDonor
 * @property boolean $largestDonation
 * @property boolean $lastDonorAndDonation
 * @property boolean $topDonor
 * @property widgetTagStyle $widgetTagStyle
 */
class WidgetTags extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_tags';
    }

    public function scenarios() {
        return [
            'default' => ['userId', 'regionNumber','lastFollower','lastSubscriber','lastDonor','largestDonation','lastDonorAndDonation','topDonor']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber','lastFollower','lastSubscriber','lastDonor','largestDonation','lastDonorAndDonation','topDonor', 'widgetTagStyle'];
    }

    public function getWidgetTagStyle(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function updateFromArray($data){
        return $this->load($data, '') && $this->save() &&
        $this->widgetTagStyle->load($data,'widgetTagStyle') &&
        $this->widgetTagStyle->save();
    }

    public function afterSave($insert, $params = array())
    {
        parent::afterSave($insert, $params);
        if ($insert) {
            $module = new WidgetTagStyle();
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
//            $module->regionNumber =
            $module->save();
        }
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['lastFollower'] = $this->lastFollower == 1;
        $data['lastSubscriber'] = $this->lastSubscriber == 1;
        $data['lastDonor'] = $this->lastDonor == 1;
        $data['largestDonation'] = $this->largestDonation == 1;
        $data['lastDonorAndDonation'] = $this->lastDonorAndDonation == 1;
        $data['topDonor'] = $this->topDonor == 1;

        return $data;
    }
}
