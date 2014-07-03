<?php

use yii\db\Schema;
use common\models\notifications\SystemNotification;
use common\models\notifications\RecentActivityNotification;

class m140703_121408_notifications extends \console\models\ExtendedMigration
{
    public function up()
    {
         $statuses = implode('","', SystemNotification::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . SystemNotification::STATUS_ACTIVE . '"';
            
        $this->createTable(SystemNotification::tableName(), [
            'id' => Schema::TYPE_PK,
            'status' => $statuses,
            'message' => Schema::TYPE_TEXT. ' NOT NULL',
            'date' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);
        
        $this->createIndex('SYSTEM_NOTIFICATION_DATE', SystemNotification::tableName(), ['date']);
        
        $this->createTable(RecentActivityNotification::tableName(),[
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'message' => Schema::TYPE_STRING. ' NOT NULL',
            'date'  => Schema::TYPE_INTEGER. ' NOT NULL'
        ]);
        
        $this->createIndex('RECENT_ACTIVITY_NOTIFICATION_USERID_DATE', RecentActivityNotification::tableName(), ['userId', 'date']);
        
        $this->sampleData();
    }

    public function sampleData(){
        $notification = new SystemNotification();
        $notification->message = 'Welcome to Pullr Alpha Test. Thank you for your collaboration.';
        $notification->save();
    }
    
    public function down()
    {
        $this->dropTable(SystemNotification::tableName());
        $this->dropTable(RecentActivityNotification::tableName());
    }
}
