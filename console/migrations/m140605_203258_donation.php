<?php

use yii\db\Schema;
use common\models\Donation;

class m140605_203258_donation extends \console\models\ExtendedMigration
{
    public function up()
    {
        $this->createTable(Donation::tableName(), [
            'id' => Schema::TYPE_PK,
            'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER,
            'amount' => Schema::TYPE_FLOAT. ' NOT NULL',
            'name' => Schema::TYPE_STRING. ' NOT NULL',
            'email' => Schema::TYPE_STRING. ' NOT NULL',
            'comments' => Schema::TYPE_TEXT. ' NOT NULL',
            'createdDate' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable(Donation::tableName());
    }
}
