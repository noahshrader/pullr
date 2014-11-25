<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetTags;

class m141118_034839_add_streamboard_widget_tags_table extends Migration
{
    public function up()
    {

        $regionNumbers = implode('","', [StreamboardRegion::REGION_NUMBER_1, StreamboardRegion::REGION_NUMBER_2]);
        $regionNumbers = "ENUM (\"$regionNumbers\") NOT NULL";

        $this->createTable(WidgetTags::tableName(), [
            'userId' => Schema::TYPE_INTEGER.' NOT NULL',
            'regionNumber' => $regionNumbers,
            'lastFollower' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'lastSubscriber' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'lastDonor' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'largestDonation' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'lastDonorAndDonation' => Schema::TYPE_BOOLEAN.' NOT NULL',
            'topDonor' => Schema::TYPE_BOOLEAN.' NOT NULL'
        ]);

        $this->addPrimaryKey('streamboard_widget_tag', WidgetTags::tableName(), ['userId', 'regionNumber']);

    }

    public function down()
    {
        $this->dropTable(WidgetTags::tableName());
        return false;
    }
}
