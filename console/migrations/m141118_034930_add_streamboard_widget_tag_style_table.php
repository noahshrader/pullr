<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetTagStyle;

class m141118_034930_add_streamboard_widget_tag_style_table extends Migration
{
    public function up()
    {
        $regionNumbers = implode('","', [StreamboardRegion::REGION_NUMBER_1, StreamboardRegion::REGION_NUMBER_2]);
        $regionNumbers = "ENUM (\"$regionNumbers\") NOT NULL";

        $this->createTable(WidgetTagStyle::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'fontStyle' => Schema::TYPE_STRING.' NOT NULL',
            'fontSize' => Schema::TYPE_INTEGER.' DEFAULT "14" NOT NULL',
            'fontWeight' => Schema::TYPE_INTEGER.' NOT NULL',
            'fontColor' => Schema::TYPE_STRING.' DEFAULT "fff" NOT NULL',
            'fontUppercase' => Schema::TYPE_BOOLEAN.' NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable(WidgetTagStyle::tableName());
        return false;
    }
}
