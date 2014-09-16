<?php

use yii\db\Schema;
use yii\db\Migration;

class m140915_074910_ipn_table extends Migration
{
    public function up()
    {
        //$this->createTable(\common\models\Ipn::tableName(), [
       //     'profileId' => Schema::TYPE_PK,
       //     'rawData' => Schema::TYPE_TEXT
       // ]);
    }

    public function down()
    {
        $this->dropTable(\common\models\Ipn::tableName());
    }
}
