<?php

namespace frontend\models\streamboard;

use common\models\Plan;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property string $backgroundColor
 * @property string $widgetType
 * @property WidgetAlerts $widgetAlerts
 * @property WidgetDonationFeed $widgetDonationFeed
 * @property WidgetCampaignBar $widgetCampaignBar
 */
class StreamboardRegion extends ActiveRecord {
    const REGION_NUMBER_1 = 1;
    const REGION_NUMBER_2 = 2;

    const WIDGET_ALERTS = 'widget_alerts';
    const WIDGET_CAMPAIGN_BAR = 'widget_campaign_bar';
    const WIDGET_DONATION_FEED = 'widget_donation_feed';

    public static $WIDGETS = [self::WIDGET_ALERTS, self::WIDGET_CAMPAIGN_BAR, self::WIDGET_DONATION_FEED];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_region';
    }

    public function scenarios() {
        return [
            'default' => ['backgroundColor', 'widgetType']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber', 'backgroundColor', 'widgetType', 'widgetAlerts', 'widgetCampaignBar', 'widgetDonationFeed'];
    }
    public function afterSave($insert, $params = array())
    {
        parent::afterSave($insert, $params);
        if ($insert) {
            /** so we have new record*/
            $this->backgroundColor = '#000';
            $widgetAlerts = new WidgetAlerts();
            $widgetAlerts->userId = $this->userId;
            $widgetAlerts->regionNumber = $this->regionNumber;
            $widgetAlerts->save();

            $widgetDonationFeed = new WidgetDonationFeed();
            $widgetDonationFeed->userId = $this->userId;
            $widgetDonationFeed->regionNumber = $this->regionNumber;
            $widgetDonationFeed->save();

            $widgetCampaignBar = new WidgetCampaignBar();
            $widgetCampaignBar->userId = $this->userId;
            $widgetCampaignBar->regionNumber = $this->regionNumber;
            $widgetCampaignBar->save();
        }
    }

    public function getWidgetAlerts(){
        return $this->hasOne(WidgetAlerts::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function getWidgetCampaignBar(){
        return $this->hasOne(WidgetCampaignBar::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function getWidgetDonationFeed(){
        return $this->hasOne(WidgetDonationFeed::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function updateFromArray($data){
        return $this->load($data, '') && $this->save() &&
            $this->widgetAlerts->updateFromArray($data['widgetAlerts']) &&
            $this->widgetCampaignBar->updateFromArray($data['widgetCampaignBar']) &&
            $this->widgetDonationFeed->load($data,'widgetDonationFeed') &&
            $this->widgetDonationFeed->save();
    }

    /**
     * @param $user User
     * @return StreamboardRegion[]
     */
    public static function GetRegions(User $user){
        $regionsNumbers = [1];
        if ($user->getPlan() == Plan::PLAN_PRO) {
            $regionsNumbers[] = 2;
        }
        $regions = StreamboardRegion::findAll(['userId' => $user->id, 'regionNumber' => $regionsNumbers]);
        if (sizeof($regions) == 0){
            for ($i = 1; $i <=2; $i++){
                $region = new StreamboardRegion();
                $region->userId = $user->id;
                $region->regionNumber = $i;
                $region->save();
                if (in_array($i,$regionsNumbers)){
                    $regions[] = $region;
                }
            }
        }
        return $regions;
    }
}
