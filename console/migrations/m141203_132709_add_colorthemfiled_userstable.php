<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\User;

class m141203_132709_add_colorthemfiled_userstable extends Migration
{
    const FIELD = 'colorTheme';
    public function up()
    {
        $this->addColumn(common\models\User::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_STRING . '(20) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(common\models\User::tableName(), self::FIELD);
        echo "m141203_132709_add_colorthemfiled_userstable cannot be reverted.\n";
    }
}
