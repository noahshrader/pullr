<?php

use yii\db\Schema;
use yii\db\Migration;

class m141006_103821_recurring_profile_column_add extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\RecurringProfile::tableName(), 'status', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn(\common\models\RecurringProfile::tableName(), 'status');
    }
}
