<?php

use yii\db\Schema;
use common\models\Layout;
use common\models\LayoutTeam;

class m140322_064650_layout extends \console\models\ExtendedMigration
{
	public function up()
	{
            $statuses = implode('","', Layout::$STATUSES);
            $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Layout::STATUS_ACTIVE . '"';
        
            $streamServices = implode('","', Layout::$STREAM_SERVICES);
            $streamServices = "ENUM (\"$streamServices\") NOT NULL";
             
            $types = implode('","', Layout::$TYPES);
            $types = "ENUM (\"$types\") NOT NULL";
             
            $this->createTable(Layout::tableName(), [
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => $statuses,
            'domain' => Schema::TYPE_STRING. ' NOT NULL',
            'streamService' => $streamServices,
            'type' => $types,
            'channelName' => Schema::TYPE_STRING. ' NOT NULL',
            'channelTeam' => Schema::TYPE_STRING. ' NOT NULL',
            'appearance' => Schema::TYPE_STRING. ' NOT NULL',   
            'chat' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'chatToggle' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'enableDonations' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'eventId' => Schema::TYPE_INTEGER,
            'theme' => Schema::TYPE_STRING. ' NOT NULL',
            'photoId' => Schema::TYPE_INTEGER,
            'primary' => Schema::TYPE_STRING. ' NOT NULL',
            'secondary' => Schema::TYPE_STRING. ' NOT NULL',
            'tertiary' => Schema::TYPE_STRING. ' NOT NULL',
            'enableTwitter' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'twitterName' => Schema::TYPE_STRING. ' NOT NULL',     
            'enableFacebook' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'twitterName' => Schema::TYPE_STRING. ' NOT NULL',     
            'enableYoutube' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'includeYoutubeChannel' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'youtubeChannel' => Schema::TYPE_STRING. ' NOT NULL',
            'youtubeLayout' => Schema::TYPE_STRING. ' NOT NULL',
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                ]);
             
             $this->createIndex('LAYOUT_BY_USER', Layout::tableName(), ['userId']);
             
             $this->createTable(LayoutTeam::tableName(), [
                 'layoutId' =>  Schema::TYPE_INTEGER. ' NOT NULL',
                 'name' => Schema::TYPE_STRING. ' NOT NULL',
                 'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
             ]);
             
             $this->addPrimaryKey('PRIMERY_KEY', LayoutTeam::tableName(), ['layoutId', 'name']);
             
             $this->sampleData(); 
	}

        public function sampleData(){
            $layout = new Layout;
            $layout->name = 'Fun For Freedom';
            $layout->type = Layout::TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Layout;
            $layout->name = 'LinkSonicK';
            $layout->type = Layout::TYPE_SINGLE;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Layout;
            $layout->name = 'Awesome Games Done Quick';
            $layout->type = Layout::TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Layout;
            $layout->name = 'Awesome Games Done Quick';
            $layout->type = Layout::TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Layout;
            $layout->name = 'Awesome Games Done Quick';
            $layout->type = Layout::TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Layout;
            $layout->name = 'Awesome Games Done Quick';
            $layout->type = Layout::TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
        }
	public function down()
	{
            $this->dropTable(LayoutTeam::tableName());
            $this->dropTable(Layout::tableName());
	}
}