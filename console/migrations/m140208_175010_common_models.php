<?php

use yii\db\Schema;
use common\models\base\BaseImage;
use common\models\Payment;
use common\models\User;
use common\models\Charity;

class m140208_175010_common_models extends \console\models\ExtendedMigration {

    public function up() {
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
                ]);
        
        $statuses = implode('","', Payment::$_STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Payment::STATUS_PENDING . '"';
        $this->createTable(Payment::tableName(), [
            'id' => Schema::TYPE_PK,
            'status' => $statuses, 
            'userId' => Schema::TYPE_INTEGER,
            'amount' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL',
            'paypalId' => Schema::TYPE_STRING,
            'createdDate' => Schema::TYPE_INTEGER. ' NOT NULL',
            'paymentDate' => Schema::TYPE_INTEGER. ' NOT NULL',
            'type' => Schema::TYPE_STRING,
            'relatedId' => Schema::TYPE_INTEGER
        ]);
        

        $statuses = implode('","', Charity::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Charity::STATUS_ACTIVE . '"';
        $this->createTable(Charity::tableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => $statuses, 
            'photoId' => Schema::TYPE_INTEGER,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'paypal' => Schema::TYPE_STRING. ' NOT NULL',
            'url' => Schema::TYPE_STRING. ' NOT NULL',
            'contact' => Schema::TYPE_STRING. ' NOT NULL',
            'contactEmail' => Schema::TYPE_STRING. ' NOT NULL',
            'contactPhone' => Schema::TYPE_STRING. ' NOT NULL',
            'description' => Schema::TYPE_TEXT. ' NOT NULL',
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
        ]);
    }


    public function down() {
        $this->dropTable(BaseImage::tableName());
        $this->dropTable(Payment::tableName());
        $this->dropTable(Charity::tableName());
    }

}
