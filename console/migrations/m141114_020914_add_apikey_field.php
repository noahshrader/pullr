<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\User;

class m141114_020914_add_apikey_field extends Migration
{
    const FIELD = 'apiKey';
    public function up()
    {
        $this->addColumn(common\models\User::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(common\models\User::tableName(), self::FIELD);
        echo "m141114_020914_add_apikey_field cannot be reverted.\n";
        return false;
    }
}