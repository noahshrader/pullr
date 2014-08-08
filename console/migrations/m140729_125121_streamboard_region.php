<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlerts;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBar;
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
            'animationDelaySeconds' => Schema::TYPE_FLOAT.' NOT NULL',
        ]);

        $this->addPrimaryKey('streamboard_widget_alerts', WidgetAlerts::tableName(), ['userId', 'regionNumber']);

        $preferencesTypes = implode('","', WidgetAlerts::$PREFERENCES_TYPES);
        $preferencesTypes = "ENUM (\"$preferencesTypes\")";
        $this->createTable(WidgetAlertsPreference::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'preferenceType' => $preferencesTypes,
            'fontStyle' => Schema::TYPE_STRING.' NOT NULL',
            'fontSize' => Schema::TYPE_INTEGER.' NOT NULL',
            'fontColor' => Schema::TYPE_STRING.' NOT NULL',
            'animationDuration' => Schema::TYPE_FLOAT.' NOT NULL',
            'volume' => Schema::TYPE_FLOAT.' NOT NULL',
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
            'fontColor' => Schema::TYPE_STRING. ' NOT NULL',
            'scrolling' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'scrollSpeed' => $scrollSpeeds
        ]);
        $this->addPrimaryKey('streamboard_widget_donation_feed', WidgetDonationFeed::tableName(), ['userId', 'regionNumber']);

        $this->createTable(WidgetCampaignBar::tableName(),[
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'regionNumber' => Schema::TYPE_INTEGER. ' NOT NULL',
            'campaignId' => Schema::TYPE_INTEGER,
            'fontStyle' => Schema::TYPE_STRING. ' NOT NULL',
            'fontSize' => Schema::TYPE_STRING. ' NOT NULL',
            'fontColor' => Schema::TYPE_STRING. ' NOT NULL',
            'backgroundColor' => Schema::TYPE_STRING. ' NOT NULL',
        ]);
        $this->addPrimaryKey('streamboard_widget_campaign_bar', WidgetCampaignBar::tableName(), ['userId', 'regionNumber']);
    }

    public function down()
    {
        $this->dropTable(StreamboardRegion::tableName());
        $this->dropTable(WidgetAlerts::tableName());
        $this->dropTable(WidgetAlertsPreference::tableName());
        $this->dropTable(WidgetDonationFeed::tableName());
        $this->dropTable(WidgetCampaignBar::tableName());
    }
}
