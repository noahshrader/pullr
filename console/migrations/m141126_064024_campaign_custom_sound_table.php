<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBarAlertsCustomsound;

class m141126_064024_campaign_custom_sound_table extends Migration
{

    public function up()
    {
        $regionNumbers = implode('","', [StreamboardRegion::REGION_NUMBER_1, StreamboardRegion::REGION_NUMBER_2]);
        $regionNumbers = "ENUM (\"$regionNumbers\") NOT NULL";

        $this->createTable(WidgetCampaignBarAlertsCustomsound::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'fileName' => Schema::TYPE_STRING.' NOT NULL',
            'donationAmount' => Schema::TYPE_STRING.' NOT NULL'
        ]);

        $this->addPrimaryKey('streamboard_widget_tag', WidgetCampaignBarAlertsCustomsound::tableName(), ['userId', 'regionNumber','fileName']);
    }

    public function down()
    {
        $this->dropTable(WidgetCampaignBarAlertsCustomsound::tableName());
    }
}
