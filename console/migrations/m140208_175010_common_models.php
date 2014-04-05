<?php

use yii\db\Schema;
use common\models\base\BaseImage;
use common\models\Payment;
use common\models\User;
use common\models\Event;
use common\models\Charity;

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
        
        $statuses = implode('","', Payment::$_STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Payment::STATUS_PENDING . '"';
        $this->createTable(Payment::tableName(), [
            'id' => Schema::TYPE_PK,
            'status' => $statuses, 
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'amount' => Schema::TYPE_FLOAT . ' NOT NULL',
            'paypalId' => Schema::TYPE_STRING,
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            'type' => Schema::TYPE_STRING
        ], $tableOptions);
        
        $user = User::find()->where(['name' => 'Stanislav'])->one();
        $user->prolong(\Yii::$app->params['yearSubscription']);
        
        $user = User::find()->where(['name' => 'Admin'])->one();
        $user->prolong(\Yii::$app->params['monthSubscription']);
        
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
        
        $statuses = implode('","', Event::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Event::STATUS_INACTIVE . '"';
        $this->createTable(Event::tableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => $statuses, 
            'charityId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'startDate' => Schema::TYPE_INTEGER . ' NOT NULL',
            'endDate' =>   Schema::TYPE_INTEGER . ' NOT NULL',
            'amountRaised' => Schema::TYPE_FLOAT . ' NOT NULL',
            'goalAmount' => Schema::TYPE_FLOAT . ' NOT NULL',
            'numberOfDonations' => Schema::TYPE_INTEGER. ' NOT NULL',
            'numberOfUniqueDonors' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            'type' => Schema::TYPE_STRING
        ], $tableOptions);
        
        
        $this->sampleData();
    }

    public function sampleData(){
        $charity = new Charity();
        $charity->name = 'St. Jude';
        $charity->type = 'Community';
        $charity->paypal = 'paypal@stjude.org';
        $charity->contact = 'Noah Shrader';
        $charity->contactEmail = 'noahshrader@gmail.com';
        $charity->save();
        
        $charity = new Charity();
        $charity->name = 'PETA';
        $charity->type = 'Animals';
        $charity->paypal = 'paypal@peta.org';
        $charity->contact = 'Stanislav Klyukin';
        $charity->contactEmail = 'stas.msu@gmail.com';
        $charity->save();
        
        $event = new Event();
        $event->name = 'Zeldathon St. Jude';
        $event->charityId = 1;
        $event->startDate = strtotime('27-12-2013');
        $event->endDate = strtotime('01-01-2014');
        $event->userId = 1;
        $event->status = Event::STATUS_ACTIVE;
        $event->save();
        
        $event = new Event();
        $event->name = 'Zeldathon St. Jude Admin';
        $event->charityId = 1;
        $event->startDate = strtotime('27-12-2013');
        $event->endDate = strtotime('01-06-2014');
        $event->userId = 5; //admin
        $event->status = Event::STATUS_ACTIVE;
        $event->amountRaised = '1233.13';
        $event->goalAmount = '1500';
        $event->save();
        
        
        $event = new Event();
        $event->name = 'Zeldathon St. Jude Admin 2';
        $event->charityId = 2;
        $event->startDate = strtotime('27-12-2013');
        $event->endDate = strtotime('01-06-2014');
        $event->userId = 5; //admin
        $event->status = Event::STATUS_ACTIVE;
        $event->amountRaised = '111.11';
        $event->goalAmount = '2000';
        $event->save();
        
    }
    
    public function down() {
        $this->dropTable(BaseImage::tableName());
        $this->dropTable(Payment::tableName());
        $this->dropTable(Charity::tableName());
        $this->dropTable(Event::tableName());
    }

}
