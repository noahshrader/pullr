<?php

use yii\db\Migration;
use frontend\models\site\DeactivateAccount;
use yii\db\Schema;

class m150121_170917_deactivate_account_pk extends Migration
{
    public function up()
    {
        $this->addColumn(DeactivateAccount::tableName(), 'id', Schema::TYPE_PK . ' FIRST');
    }

    public function down()
    {
        $this->dropColumn(DeactivateAccount::tableName(), 'id');
    }
}
