<?php

use yii\db\Schema;
use common\models\Campaign;
use common\models\LayoutTeam;
use common\models\CampaignInvite;

class m140322_064650_campaign extends \console\models\ExtendedMigration
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
             
            $donationDestinations = implode('","', Campaign::$DONATION_DESTINATIONS);
            $donationDestinations = "ENUM (\"$donationDestinations\") NOT NULL";
            
            $this->createTable(Campaign::tableName(), [
            'id' => Schema::TYPE_PK,
             /* equal to id if this is not child campaign */
            'parentCampaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'tiedToParent' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'alias' => Schema::TYPE_STRING. ' NOT NULL',
            'key' => Schema::TYPE_STRING. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'type' => $types, 
            'startDate' => Schema::TYPE_INTEGER,
            'endDate' => Schema::TYPE_INTEGER,
            'amountRaised' => Schema::TYPE_FLOAT . ' NOT NULL',
            'goalAmount' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'numberOfDonations' => Schema::TYPE_INTEGER. ' NOT NULL',
            'numberOfUniqueDonors' => Schema::TYPE_INTEGER. ' NOT NULL',
            'paypalAddress' => Schema::TYPE_STRING,
            'donationDestination' => $donationDestinations,
            'charityId' => Schema::TYPE_INTEGER,
            'customCharity' => Schema::TYPE_STRING,
            'customCharityPaypal' => Schema::TYPE_STRING,
            'customCharityDescription' => Schema::TYPE_STRING,
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
            'eventId' => Schema::TYPE_INTEGER,
            'themeId' => Schema::TYPE_INTEGER,
            'backgroundImageId' => Schema::TYPE_INTEGER,
            'primaryColor' => Schema::TYPE_STRING. ' NOT NULL',
            'secondaryColor' => Schema::TYPE_STRING. ' NOT NULL',
            'twitterEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'twitterName' => Schema::TYPE_STRING. ' NOT NULL',     
            'facebookEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'facebookUrl' => Schema::TYPE_STRING. ' NOT NULL',     
            'youtubeEnable' => Schema::TYPE_BOOLEAN. ' NOT NULL',
            'youtubeUrl' => Schema::TYPE_STRING. ' NOT NULL',
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
                 'status' => $statuses,
                 'lastChangeDate' => Schema::TYPE_INTEGER. ' NOT NULL'
             ]);
             $this->createIndex('campaignInviteCampaignId', CampaignInvite::tableName(), ['campaignId']);
             $this->createIndex('campaignInviteUserId', CampaignInvite::tableName(), ['userId']);
             $this->createIndex('campaignInviteUnique', CampaignInvite::tableName(),['userId', 'campaignId'], true);
             $this->sampleData(); 
	}

        public function sampleData(){
            $campaign = new Campaign;
            $campaign->name = 'Fun For Freedom';
            $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->channelTeam = "funforfreedom";
            $campaign->userId = 1;
            $campaign->key = 'test_key';
            $campaign->eventId = 1;
            $campaign->goalAmount = 17000;
            $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
            $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
            $campaign->save();

            CampaignInvite::addInvite(2, $campaign->id);
            $invite = CampaignInvite::findOne(['userId' => 2, 'campaignId' => $campaign->id]);
            $invite->approve();
            
            $campaign = new Campaign;
            $campaign->name = 'Fun For Freedom 2';
            $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->channelTeam = "funforfreedom";
            $campaign->userId = 2;
            $campaign->key = 'test_key';
            $campaign->eventId = 1;
            $campaign->goalAmount = 17000;
            $campaign->charityId = 1;
            $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
            $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
            $campaign->save();
            
            CampaignInvite::addInvite(1, $campaign->id);
            
            $campaign = new Campaign;
            $campaign->name = 'LinkSonicK';
            $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_SINGLE;
            $campaign->userId = 1;
            $campaign->goalAmount = 15000;
            $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
            $campaign->customCharityPaypal = 'wrong.paypal@gmail.com';
            $campaign->save();
            
            $campaign = new Campaign();
            $campaign->name = 'Awesome Games Done Quick';
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->userId = 1;
            $campaign->goalAmount = 15000;
            $campaign->save();
            
            $campaign = new Campaign();
            $campaign->name = 'Awesome Games Done Quick 2';
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->userId = 1;
            $campaign->goalAmount = 15000;
            $campaign->save();
            
            
            $campaign = new Campaign;
            $campaign->name = 'Awesome Games Done Quick';
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->userId = 5;
            $campaign->goalAmount = 15000;
            $campaign->save();   
            
            CampaignInvite::addInvite(1, $campaign->id);
            $invite = CampaignInvite::findOne(['userId' => 1, 'campaignId' => $campaign->id]);
            $invite->approve();
            
            $campaign = new Campaign;
            $campaign->name = 'Parent Campaign';
            $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->userId = 5;
            $campaign->goalAmount = 15000;
            $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
            $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
            $campaign->save();
            
            CampaignInvite::addInvite(1, $campaign->id);
            $invite = CampaignInvite::findOne(['userId' => 1, 'campaignId' => $campaign->id]);
            $invite->approve();
            
            $parentCampaignId = $campaign->id;
            
            $campaign = new Campaign();
            $campaign->name = 'Fun For Freedom Child';
            $campaign->type = Campaign::TYPE_CHARITY_EVENT;
            $campaign->tiedToParent = true;
            $campaign->parentCampaignId = $parentCampaignId;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->goalAmount = 15000;
            $campaign->userId = 1;
            $campaign->startDate = 1405761880;
            $campaign->endDate = 1408761880;
            
            $campaign->save();

            $campaign = new Campaign();
            $campaign->name = 'Fun For Freedom Child 2';
            $campaign->type = Campaign::TYPE_CHARITY_EVENT;
            $campaign->tiedToParent = true;
            $campaign->parentCampaignId = $parentCampaignId;
            $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
            $campaign->userId = 1;
            $campaign->goalAmount = 15000;
            $campaign->save();
        }
	public function down()
	{
            $this->dropTable(LayoutTeam::tableName());
            $this->dropTable(Campaign::tableName());
            $this->dropTable(CampaignInvite::tableName());
	}
}
