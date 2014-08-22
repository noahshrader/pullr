<?php

use yii\db\Schema;
use yii\db\Migration;

class m140821_094840_team_enable extends Migration
{
    const COLUMN_TEAM_ENABLE = 'teamEnable';

    public function up()
    {
        $this->addColumn(\common\models\Campaign::tableName(), self::COLUMN_TEAM_ENABLE, Schema::TYPE_BOOLEAN . ' DEFAULT 0 AFTER secondaryColor');
    }

    public function down()
    {
        $this->dropColumn(\common\models\Campaign::tableName(), self::COLUMN_TEAM_ENABLE);
    }
}
