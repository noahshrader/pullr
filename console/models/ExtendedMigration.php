<?php

namespace console\models;
use yii\db\Migration;

class ExtendedMigration extends Migration {
    public function dropTable($table) {
        if ($this->db->schema->getTableSchema($table, true) !== null) {
            parent::dropTable($table);
        }
    }
    
    public function createTable($table, $columns, $options = null) {
            if ($options == null)
            $options = null;
            if ($this->db->driverName === 'mysql') {
                $options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            }

            parent::createTable($table, $columns, $options);
    }
}
