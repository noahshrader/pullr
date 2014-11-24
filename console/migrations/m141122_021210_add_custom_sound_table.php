<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlertsCustomsound;


class m141122_021210_add_custom_sound_table extends Migration
{
    public function up()
    {
    	$regionNumbers = implode('","', [StreamboardRegion::REGION_NUMBER_1, StreamboardRegion::REGION_NUMBER_2]);
        $regionNumbers = "ENUM (\"$regionNumbers\") NOT NULL";

        $this->createTable(WidgetAlertsCustomsound::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,            
            'fileName' => Schema::TYPE_STRING.' NOT NULL',
            'donationAmount' => Schema::TYPE_STRING.' NOT NULL'
        ]);

        $this->addPrimaryKey('streamboard_widget_tag', WidgetAlertsCustomsound::tableName(), ['userId', 'regionNumber','fileName']);
    }

    public function down()
    {
       $this->dropTable(WidgetAlertsCustomsound::tableName());
       return false;
    }
}
