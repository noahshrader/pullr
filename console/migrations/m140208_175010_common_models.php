<?php

use common\models\OpenIDToUser;
use yii\db\Schema;

class m140208_175010_common_models extends \yii\db\Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(OpenIDToUser::tableName(), [
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'serviceName' => Schema::TYPE_STRING . ' NOT NULL',
            'serviceId' => Schema::TYPE_STRING . ' NOT NULL',
            'url' => Schema::TYPE_STRING
                ], $tableOptions);
        $this->addPrimaryKey('PRIMERY_KEY', OpenIDToUser::tableName(), ['serviceName', 'serviceId']);
    }

    public function down() {
        $this->dropTable(OpenIDToUser::tableName());
    }

}
