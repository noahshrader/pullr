<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardConfig;
use frontend\models\streamboard\StreamboardCampaign;
use frontend\models\streamboard\StreamboardDonation;

class m140314_095651_streamboard extends ExtendedMigration
{
    public function up()
    {
        $this->createTable(StreamboardConfig::tableName(), [
           'userId' => Schema::TYPE_PK,
            'streamboardWidth' => Schema::TYPE_INTEGER .' NOT NULL',
            'streamboardHeight' => Schema::TYPE_INTEGER . ' NOT NULL',
            'streamboardLeft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'streamboardTop' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);

        $this->createTable(StreamboardCampaign::tableName(), [
            'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'selected' => Schema::TYPE_BOOLEAN. ' DEFAULT TRUE NOT NULL'
        ]);
        /*same campaign can be viewed at streamboard by few users, if that is child campaign (event)*/
        $this->addPrimaryKey('streamboard_campaigns_primary', StreamboardCampaign::tableName(), ['campaignId', 'userId']);

        $this->createTable(StreamboardDonation::tableName(), [
            'donationId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'nameHidden' => Schema::TYPE_BOOLEAN. ' DEFAULT FALSE NOT NULL',
            'wasRead' => Schema::TYPE_BOOLEAN. ' DEFAULT FALSE NOT NULL'
        ]);
        /*we are using userId as part of primary key for the same reason as StreamboardCampaign above*/
        $this->addPrimaryKey('streamboard_donations_primary', StreamboardDonation::tableName(), ['donationId', 'userId']);
    }

    public function down()
    {
        $this->dropTable(StreamboardCampaign::tableName());
        $this->dropTable(StreamboardConfig::tableName());
        $this->dropTable(StreamboardDonation::tableName());
    }
}
