<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\site\DeactivatePro;

class m140919_102615_deactivatepro extends Migration
{
    public function up()
    {
        $this->createTable(DeactivatePro::tableName(), [
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reason' => Schema::TYPE_TEXT,
            'creationDate' => Schema::TYPE_INTEGER,
        ]);
        $this->createIndex('deactivateUserId', DeactivatePro::tableName(), ['userId']);
    }

    public function down()
    {
        $this->dropTable(DeactivatePro::tableName());
    }
}
