<?php

use yii\db\Schema;
use yii\db\Migration;

class m140815_110034_theme_new_column extends Migration
{
    const COLUMN_ADDED_DATE = 'addedDate';

    public function up()
    {
        $this->addColumn(\common\models\Theme::tableName(), self::COLUMN_ADDED_DATE, Schema::TYPE_INTEGER . ' NOT NULL AFTER plan');
    }

    public function down()
    {
        $this->dropColumn(\common\models\Theme::tableName(), self::COLUMN_ADDED_DATE);
    }
}
