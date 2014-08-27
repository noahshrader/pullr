<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardConfig;

class m140827_155952_streamboard_stream_fields extends ExtendedMigration
{
    public function up()
    {
        $this->addColumn(StreamboardConfig::tableName(), 'streamRequestLastDate', Schema::TYPE_INTEGER.' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(StreamboardConfig::tableName(), 'streamRequestLastDate');
    }
}
