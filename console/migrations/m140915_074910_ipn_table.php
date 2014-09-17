<?php

use yii\db\Schema;
use yii\db\Migration;

class m140915_074910_ipn_table extends Migration
{
    public function up()
    {
        $this->createTable(\common\models\Ipn::tableName(), [
            'id' => Schema::TYPE_PK,
            'txnType' => Schema::TYPE_STRING . ' NOT NULL',
            'createdDate' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rawData' => Schema::TYPE_TEXT . ' NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable(\common\models\Ipn::tableName());
    }
}
