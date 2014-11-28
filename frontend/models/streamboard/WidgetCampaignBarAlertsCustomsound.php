<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;
use common\components\streamboard\alert\AlertMediaManager;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property string $fileName
 * @property string $donationAmount
 */
class WidgetCampaignBarAlertsCustomsound  extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_campaign_bar_alerts_customsound';
    }

    public function scenarios() {
        return [
            'default' => ['fileName','donationAmount']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber','fileName','donationAmount'];
    }
    
    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        return $data;
    }

    public static function updateFromArray($data){
        $userId = $data['userId'];
        $regionNumber = $data['regionNumber'];
        WidgetCampaignBarAlertsCustomsound ::deleteAll(['userId' => $userId, 'regionNumber' => $regionNumber]);
        $data = $data['donationCustomsound'];
        foreach($data as $row){
            $recode = new WidgetCampaignBarAlertsCustomsound ();
            $recode->userId = $userId;
            $recode->regionNumber = $regionNumber;
            $recode->fileName = $row['fileName'];
            $recode->donationAmount = $row['donationAmount'];
            $recode->save();
        }
        return true;
    }
}
