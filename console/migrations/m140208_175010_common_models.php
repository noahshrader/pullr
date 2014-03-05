<?php

use yii\db\Schema;

class m140208_175010_common_models extends \yii\db\Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

       
    }

    public function down() {
    }

}
