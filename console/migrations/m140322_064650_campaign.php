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
	}

	public function down()
	{
            $this->dropTable(LayoutTeam::tableName());
            $this->dropTable(Campaign::tableName());
            $this->dropTable(CampaignInvite::tableName());
	}
}
