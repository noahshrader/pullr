<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\Charity;

class m140724_032947_modify_charity extends Migration
{
    private static $columnName = 'firstGivingId';
    private static $indexName = 'CHARITY_FIRST_GIVING_ID';

    public function up()
    {
        $this->addColumn(Charity::tableName(), self::$columnName, Schema::TYPE_INTEGER . ' AFTER id');
        $this->createIndex(self::$indexName, Charity::tableName(), [self::$columnName]);
    }

    public function down()
    {
        $this->dropIndex(self::$indexName, Charity::tableName());
        $this->dropColumn(Charity::tableName(), self::$columnName);
    }
}
