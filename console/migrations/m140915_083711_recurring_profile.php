<?php

use yii\db\Schema;
use yii\db\Migration;

class m140915_083711_recurring_profile extends Migration
{
    public function up()
    {
        $this->createTable(\common\models\RecurringProfile::tableName(), [
             'profileId' => Schema::TYPE_STRING,
             'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
         ]);
        $this->addPrimaryKey('PRIMARY_KEY', \common\models\RecurringProfile::tableName(), 'profileId');
    }

    public function down()
    {
        $this->dropTable(\common\models\RecurringProfile::tableName());
    }
}
