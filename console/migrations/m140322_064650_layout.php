<?php

use yii\db\Schema;
use common\models\Campaign;
use common\models\LayoutTeam;
use common\models\CampaignInvite;

class m140322_064650_layout extends \console\models\ExtendedMigration
{
	public function up()
	{
            $types = implode('","', Campaign::$TYPES);
            $types = "ENUM (\"$types\") NOT NULL";
            
            $statuses = implode('","', Campaign::$STATUSES);
            $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Campaign::STATUS_ACTIVE . '"';
        
            $streamServices = implode('","', Campaign::$STREAM_SERVICES);
            $streamServices = "ENUM (\"$streamServices\") NOT NULL";
             
            $layoutTypes = implode('","', Campaign::$LAYOUT_TYPES);
            $layoutTypes = "ENUM (\"$layoutTypes\") NOT NULL";
             
            $this->createTable(Campaign::tableName(), [
            'id' => Schema::TYPE_PK,
            'alias' => Schema::TYPE_STRING. ' NOT NULL',
            'key' => Schema::TYPE_STRING. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'type' => $types, 
            'startDate' => Schema::TYPE_INTEGER,
            'endDate' => Schema::TYPE_INTEGER,
            'amountRaised' => Schema::TYPE_FLOAT . ' NOT NULL',
            'goalAmount' => Schema::TYPE_FLOAT,
            'numberOfDonations' => Schema::TYPE_INTEGER. ' NOT NULL',
            'numberOfUniqueDonors' => Schema::TYPE_INTEGER. ' NOT NULL',
            'paypalAddress' => Schema::TYPE_STRING,
            'donationDestination' => Schema::TYPE_STRING,
            'charityId' => Schema::TYPE_INTEGER,
            'customCharity' => Schema::TYPE_STRING,
            'customCharityPaypal' => Schema::TYPE_STRING,
            'customCharityDescription' => Schema::TYPE_STRING,
            'enableGoogleAnalytics' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'googleAnalytics' => Schema::TYPE_STRING. ' NOT NULL',
            'status' => $statuses,
            'streamService' => $streamServices,
            'layoutType' => $layoutTypes,
            'channelName' => Schema::TYPE_STRING. ' NOT NULL',
            'channelTeam' => Schema::TYPE_STRING. ' NOT NULL',
            'formVisibility' => Schema::TYPE_BOOLEAN,
            'enableDonorComments' => Schema::TYPE_BOOLEAN,
            'enableThankYouPage' => Schema::TYPE_BOOLEAN,
            'thankYouPageText' => Schema::TYPE_TEXT,
            'appearance' => Schema::TYPE_STRING. ' NOT NULL',   
            'chat' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'eventId' => Schema::TYPE_INTEGER,
            'themeId' => Schema::TYPE_INTEGER,
            'enableCustomLogo' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'photoId' => Schema::TYPE_INTEGER,
            'primaryColor' => Schema::TYPE_STRING. ' NOT NULL',
            'secondaryColor' => Schema::TYPE_STRING. ' NOT NULL',
            'tertiaryColor' => Schema::TYPE_STRING. ' NOT NULL',
            'twitterEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'twitterName' => Schema::TYPE_STRING. ' NOT NULL',     
            'facebookEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'facebookUrl' => Schema::TYPE_STRING. ' NOT NULL',     
            'youtubeEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'youtubeUrl' => Schema::TYPE_STRING. ' NOT NULL',
            'includeYoutubeFeed' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'youtubeLayout' => Schema::TYPE_STRING. ' NOT NULL',
            'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                ]);
             
             $this->createIndex('LAYOUT_BY_USER', Campaign::tableName(), ['userId']);
             
             $this->createTable(LayoutTeam::tableName(), [
                 'id' => Schema::TYPE_PK,
                 'campaignId' =>  Schema::TYPE_INTEGER. ' NOT NULL',
                 'name' => Schema::TYPE_STRING. ' NOT NULL',
                 'date' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                 'youtube' => Schema::TYPE_STRING. ' NOT NULL',
                 'twitter' => Schema::TYPE_STRING. ' NOT NULL',
                 'facebook' => Schema::TYPE_STRING. ' NOT NULL',
             ]);
             
             $this->createIndex('campaignTeamunique',  LayoutTeam::tableName(), ['campaignId', 'name'], true);
             
             
             $statuses = implode('","', CampaignInvite::$STATUSES);
             $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . CampaignInvite::STATUS_ACTIVE . '"';
             $this->createTable(CampaignInvite::tableName(), [
                 'id' => Schema::TYPE_PK,
                 'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
                 'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
                 'status' => $statuses
             ]);
             $this->createIndex('campaignInviteCampaignId', CampaignInvite::tableName(), ['campaignId']);
             $this->createIndex('campaignInviteUserId', CampaignInvite::tableName(), ['userId']);
             $this->createIndex('campaignInviteUnique', CampaignInvite::tableName(),['userId', 'campaignId'], true);
             $this->sampleData(); 
	}

        public function sampleData(){
            $layout = new Campaign;
            $layout->name = 'Fun For Freedom';
            $layout->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $layout->channelTeam = "funforfreedom";
            $layout->userId = 1;
            $layout->key = 'test_key';
            $layout->eventId = 1;
            $layout->save();
            
            $layout = new Campaign;
            $layout->name = 'LinkSonicK';
            $layout->layoutType = Campaign::LAYOUT_TYPE_SINGLE;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Campaign;
            $layout->name = 'Awesome Games Done Quick';
            $layout->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Campaign;
            $layout->name = 'Awesome Games Done Quick';
            $layout->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Campaign;
            $layout->name = 'Awesome Games Done Quick';
            $layout->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
            
            $layout = new Campaign;
            $layout->name = 'Awesome Games Done Quick';
            $layout->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $layout->userId = 1;
            $layout->save();
        }
	public function down()
	{
            $this->dropTable(LayoutTeam::tableName());
            $this->dropTable(Campaign::tableName());
            $this->dropTable(CampaignInvite::tableName());
	}
}
