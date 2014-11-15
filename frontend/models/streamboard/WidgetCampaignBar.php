<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $alertsEnable
 * @property boolean $messagesEnable
 * @property boolean $timerEnable
 * @property boolean $progressBarEnable
 * @property string $fontColor
 * @property integer $height
 * @property integer $width
 * @property WidgetCampaignBarAlerts $alertsModule
 * @property WidgetCampaignBarMessages $messagesModule
 * @property WidgetCampaignBarTimer $timerModule
 * @property WidgetCampaignBarCurrentTotal $currentTotalModule
 */
class WidgetCampaignBar extends ActiveRecord
{
    const DEFAULT_WIDTH = 900;
    const DEFAULT_HEIGHT = 100;
    const MIN_HEIGHT = 100;
    const MIN_WIDTH = 200;

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'tbl_streamboard_widget_campaign_bar';
    }

    public function scenarios()
    {
        return [
            'default' => ['campaignId', 'fontStyle', 'fontSize', 'fontUppercase', 'fontColor', 'backgroundColor', 'alertsEnable',
                'messagesEnable', 'timerEnable', 'progressBarEnable', 'positionX', 'positionY', 'height', 'width',
                'background']
        ];
    }

    public function fields()
    {
        return ['campaignId', 'fontStyle', 'fontSize', 'fontColor', 'backgroundColor', 'alertsEnable',
            'messagesEnable', 'timerEnable', 'progressBarEnable', 'alertsModule', 'messagesModule', 'timerModule', 
            'currentTotalModule', 'positionX', 'positionY', 'height', 'width',
            'background'];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->fontColor = '#FFFFFF';
            $this->height = self::DEFAULT_HEIGHT;
            $this->width = self::DEFAULT_WIDTH;
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $params = array())
    {
        parent::afterSave($insert, $params);
        if ($insert) {
            /** so we have new record*/
            $module = new WidgetCampaignBarAlerts();
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->save();

            $module = new WidgetCampaignBarMessages();
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->save();

            $module = new WidgetCampaignBarTimer();
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->save();

            $module = new WidgetCampaignBarCurrentTotal();
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->save();
        }
    }

    public function getAlertsModule()
    {
        return $this->hasOne(WidgetCampaignBarAlerts::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function getMessagesModule()
    {
        return $this->hasOne(WidgetCampaignBarMessages::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function getTimerModule()
    {
        return $this->hasOne(WidgetCampaignBarTimer::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function getCurrentTotalModule()
    {
        return $this->hasOne(WidgetCampaignBarCurrentTotal::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber']);
    }

    public function updateFromArray($data)
    {
        return $this->load($data, '') && $this->save() &&
        $this->alertsModule->load($data, 'alertsModule') &&
        $this->alertsModule->save() &&
        $this->messagesModule->load($data, 'messagesModule') &&
        $this->messagesModule->save() &&
        $this->timerModule->load($data, 'timerModule') &&
        $this->timerModule->save() &&
        $this->currentTotalModule->load($data, 'currentTotalModule') &&
        $this->currentTotalModule->save();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['alertsEnable'] = $this->alertsEnable == 1;
        $data['messagesEnable'] = $this->messagesEnable == 1;
        $data['timerEnable'] = $this->timerEnable == 1;
        $data['progressBarEnable'] = $this->progressBarEnable == 1;
        $data['fontUppercase'] = $this->fontUppercase == 1;
        return $data;
    }
}
