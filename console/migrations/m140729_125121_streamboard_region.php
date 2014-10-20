<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlerts;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBar;
use frontend\models\streamboard\WidgetCampaignBarAlerts;
use frontend\models\streamboard\WidgetCampaignBarMessages;
use frontend\models\streamboard\WidgetCampaignBarTimer;
use frontend\models\streamboard\WidgetDonationFeed;

class m140729_125121_streamboard_region extends ExtendedMigration
{
    public function up()
    {
        $regionNumbers = implode('","', [StreamboardRegion::REGION_NUMBER_1, StreamboardRegion::REGION_NUMBER_2]);
        $regionNumbers = "ENUM (\"$regionNumbers\") NOT NULL";

        $widgetTypes = implode('","', StreamboardRegion::$WIDGETS);
        $widgetTypes = "ENUM (\"$widgetTypes\")";
        $this->createTable(StreamboardRegion::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'backgroundColor' => Schema::TYPE_STRING. ' NOT NULL',
            'widgetType' => $widgetTypes
        ]);

        $this->addPrimaryKey('streamboard_region_primary', StreamboardRegion::tableName(), ['userId', 'regionNumber']);

        $this->createTable(WidgetAlerts::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'includeFollowers' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'includeSubscribers' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'includeDonations' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'animationDelaySeconds' => Schema::TYPE_INTEGER.' NOT NULL',
            'positionX' => Schema::TYPE_INTEGER.' NOT NULL',
            'positionY' => Schema::TYPE_INTEGER.' NOT NULL'
        ]);

        $this->addPrimaryKey('streamboard_widget_alerts', WidgetAlerts::tableName(), ['userId', 'regionNumber']);

        $preferencesTypes = implode('","', WidgetAlerts::$PREFERENCES_TYPES);
        $preferencesTypes = "ENUM (\"$preferencesTypes\")";

        $fileTypes = implode('","', WidgetAlertsPreference::$FILE_TYPES);
        $fileTypes = "ENUM (\"$fileTypes\")";
        $this->createTable(WidgetAlertsPreference::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'preferenceType' => $preferencesTypes,
            'fontStyle' => Schema::TYPE_STRING.' NOT NULL',
            'fontSize' => Schema::TYPE_INTEGER.' NOT NULL',
            'fontColor' => Schema::TYPE_STRING.' DEFAULT "#fff" NOT NULL',
            'animationDuration' => Schema::TYPE_INTEGER.' NOT NULL',
            'volume' => Schema::TYPE_FLOAT.' NOT NULL DEFAULT 100',
            'sound' => Schema::TYPE_STRING.' NOT NULL',
            'soundType' => $fileTypes,
            'image' => Schema::TYPE_STRING.' NOT NULL',
            'imageType' => $fileTypes,
            'hideAlertText' => Schema::TYPE_BOOLEAN.' DEFAULT 0',
            'hideAlertImage' => Schema::TYPE_BOOLEAN.' DEFAULT 0',
        ]);

        $this->addPrimaryKey('streamboard_widget_alerts_preference', WidgetAlertsPreference::tableName(), ['userId', 'regionNumber', 'preferenceType']);

        $scrollSpeeds = implode('","', WidgetDonationFeed::$SCROLL_SPEEDS);
        $scrollSpeeds = "ENUM (\"$scrollSpeeds\") NOT NULL";
        $this->createTable(WidgetDonationFeed::tableName(),[
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'regionNumber' => Schema::TYPE_INTEGER. ' NOT NULL',
            'noDonationMessage' => Schema::TYPE_TEXT.' NOT NULL',
            'fontStyle' => Schema::TYPE_STRING. ' NOT NULL',
            'fontSize' => Schema::TYPE_STRING. ' NOT NULL',
            'fontColor' => Schema::TYPE_STRING. ' DEFAULT "#fff" NOT NULL',
            'scrolling' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'scrollSpeed' => $scrollSpeeds,
            'showSubscriber' => Schema::TYPE_BOOLEAN. ' DEFAULT 1',
            'showFollower' => Schema::TYPE_BOOLEAN. ' DEFAULT 1',
            'positionX'=> Schema::TYPE_INTEGER.' NOT NULL',
            'positionY' => Schema::TYPE_INTEGER.' NOT NULL'
        ]);
        $this->addPrimaryKey('streamboard_widget_donation_feed', WidgetDonationFeed::tableName(), ['userId', 'regionNumber']);


        $this->createTable(WidgetCampaignBar::tableName(),[
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'regionNumber' => Schema::TYPE_INTEGER. ' NOT NULL',
            'campaignId' => Schema::TYPE_INTEGER,
            'fontStyle' => Schema::TYPE_STRING. ' NOT NULL',
            'fontSize' => Schema::TYPE_STRING. ' NOT NULL',
            'fontColor' => Schema::TYPE_STRING. ' DEFAULT "#fff" NOT NULL',
            'backgroundColor' => Schema::TYPE_STRING. ' DEFAULT "#333" NOT NULL',
            'alertsEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'messagesEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'timerEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'progressBarEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'hideAlertText' => Schema::TYPE_BOOLEAN.' DEFAULT 0',
            'positionX'=> Schema::TYPE_INTEGER.' NOT NULL',
            'positionY' => Schema::TYPE_INTEGER.' NOT NULL',
            'width' => Schema::TYPE_INTEGER.' NOT NULL',
            'height' => Schema::TYPE_INTEGER.' NOT NULL'
        ]);
        $this->addPrimaryKey('streamboard_widget_campaign_bar', WidgetCampaignBar::tableName(), ['userId', 'regionNumber']);

        $this->createTable(WidgetCampaignBarAlerts::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'preferenceType' => $preferencesTypes,
            'includeFollowers' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'includeSubscribers' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'includeDonations' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'fontStyle' => Schema::TYPE_STRING.' NOT NULL',
            'fontSize' => Schema::TYPE_INTEGER.' NOT NULL',
            'fontColor' => Schema::TYPE_STRING.' DEFAULT "fff" NOT NULL',
            'backgroundColor' => Schema::TYPE_STRING.' NOT NULL',
            'animationDirection' => Schema::TYPE_STRING.' NOT NULL',
            'animationDuration' => Schema::TYPE_INTEGER.' NOT NULL',
            'animationDelay' => Schema::TYPE_INTEGER.' NOT NULL',
            'volume' => Schema::TYPE_FLOAT.' NOT NULL',                        
        ]);
        $this->addPrimaryKey('streamboard_widget_campaign_bar_alerts', WidgetCampaignBarAlerts::tableName(), ['userId', 'regionNumber']);

        $this->createTable(WidgetCampaignBarMessages::tableName(),[
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'regionNumber' => Schema::TYPE_INTEGER. ' NOT NULL',
            'message1' => Schema::TYPE_STRING. ' NOT NULL',
            'message2' => Schema::TYPE_STRING. ' NOT NULL',
            'message3' => Schema::TYPE_STRING. ' NOT NULL',
            'message4' => Schema::TYPE_STRING. ' NOT NULL',
            'message5' => Schema::TYPE_STRING. ' NOT NULL',
            'rotationSpeed' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);
        $this->addPrimaryKey('streamboard_widget_campaign_bar_messages', WidgetCampaignBarMessages::tableName(), ['userId', 'regionNumber']);

        $timerTypes = implode('","', WidgetCampaignBarTimer::$TIMER_TYPES);
        $timerTypes = "ENUM (\"$timerTypes\")";
        $this->createTable(WidgetCampaignBarTimer::tableName(),[
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'regionNumber' => Schema::TYPE_INTEGER. ' NOT NULL',
            'timerType' => $timerTypes,
            'countDownFrom' => Schema::TYPE_STRING. ' NOT NULL',
            'countDownTo' => Schema::TYPE_STRING. ' NOT NULL',
            'countUpStartTime' => Schema::TYPE_STRING. ' NOT NULL',
            'countUpPauseTime' => Schema::TYPE_STRING. ' NOT NULL',
            'countUpStatus' => Schema::TYPE_BOOLEAN. ' NOT NULL',
        ]);
        $this->addPrimaryKey('streamboard_widget_campaign_bar_timer', WidgetCampaignBarTimer::tableName(), ['userId', 'regionNumber']);
    }

    public function down()
    {
        $this->dropTable(StreamboardRegion::tableName());
        $this->dropTable(WidgetAlerts::tableName());
        $this->dropTable(WidgetAlertsPreference::tableName());
        $this->dropTable(WidgetDonationFeed::tableName());
        $this->dropTable(WidgetCampaignBar::tableName());
        $this->dropTable(WidgetCampaignBarAlerts::tableName());
        $this->dropTable(WidgetCampaignBarMessages::tableName());
        $this->dropTable(WidgetCampaignBarTimer::tableName());
    }
}
