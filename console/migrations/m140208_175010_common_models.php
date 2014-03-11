<?php

use yii\db\Schema;
use common\models\base\BaseImage;

class m140208_175010_common_models extends \console\models\ExtendedMigration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $statuses = implode('","', BaseImage::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . BaseImage::STATUS_APPROVED . '"';


        $types = implode('","', BaseImage::$TYPES);
        $types = "ENUM (\"$types\") NOT NULL";

        $this->createTable(BaseImage::tableName(), [
            'id' => Schema::TYPE_PK,
            'status' => $statuses,
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => $types,
            'subjectId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable(BaseImage::tableName());
    }

}
