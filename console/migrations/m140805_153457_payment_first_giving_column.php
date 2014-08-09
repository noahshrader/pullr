<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\Payment;

class m140805_153457_payment_first_giving_column extends Migration
{
    private static $columnName = 'firstGivingTransactionId';

    public function up()
    {
        $this->addColumn(Payment::tableName(), self::$columnName, Schema::TYPE_STRING . ' AFTER paypalId');
    }

    public function down()
    {
        $this->dropColumn(Payment::tableName(), self::$columnName);
    }
}
