<?php

use yii\db\Schema;
use yii\db\Migration;

class m141016_141846_payment_table_modifications extends Migration
{
    public function up()
    {
        $this->renameColumn(\common\models\Payment::tableName(), "paypalId", "payPalTransactionId");
        $this->addColumn(\common\models\Payment::tableName(), 'payKey', Schema::TYPE_STRING . " AFTER amount");
    }

    public function down()
    {
        $this->dropColumn(\common\models\Payment::tableName(), 'payKey');
        $this->renameColumn(\common\models\Payment::tableName(), "payPalTransactionId", "paypalId");
    }
}
