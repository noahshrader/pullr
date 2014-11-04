<?php

use yii\db\Schema;
use yii\db\Migration;

class m141103_050501_theme_default extends Migration
{
    const COLUMN_IS_DEFAULT = 'is_default';
    
    public function up()
    {
        $this->addColumn(\common\models\Theme::tableName(), self::COLUMN_IS_DEFAULT, \yii\db\mysql\Schema::TYPE_INTEGER. ' DEFAULT 0 AFTER plan');
    }

    public function down()
    {
        echo "m141103_050501_theme_default cannot be reverted.\n";

        return false;
    }
}
