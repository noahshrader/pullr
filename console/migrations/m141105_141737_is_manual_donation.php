<?php

use yii\db\Schema;
use yii\db\Migration;

class m141105_141737_is_manual_donation extends Migration
{
    const FIELD = 'is_manual';

    public function up()
    {
        $this->addColumn(\common\models\Donation::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_BOOLEAN. ' DEFAULT 0 AFTER userId');

    }

    public function down()
    {
        $this->dropColumn(\common\models\Donation::tableName(), self::FIELD);
    }
}
