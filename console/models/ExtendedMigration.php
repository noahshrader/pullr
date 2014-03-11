<?php

namespace console\models;

class ExtendedMigration extends \yii\db\Migration {
    public function dropTable($table) {
        if ($this->db->schema->getTableSchema($table, true) !== null) {
            parent::dropTable($table);
        }
    }
}
