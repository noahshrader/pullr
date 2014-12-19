<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardTestAlert;

class m141218_080506_add_test_alert_data_table extends Migration
{
    public function up()
    {
        $this->createTable(StreamboardTestAlert::tableName(), [
            'id' => Schema::TYPE_PK,
            'alertType' => Schema::TYPE_STRING . ' NOT NULL',
            'regionNumber' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            'userId' => Schema::TYPE_INTEGER,
            'createdAt' => Schema::TYPE_INTEGER
        ]);
    }

    public function down()
    {
        $this->dropTable(StreamboardTestAlert::tableName());
    }
}
