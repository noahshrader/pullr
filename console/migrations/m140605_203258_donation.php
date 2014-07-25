<?php

use yii\db\Schema;
use common\models\Donation;
use common\models\Campaign;

class m140605_203258_donation extends \console\models\ExtendedMigration
{
    public function up()
    {
        $this->createTable(Donation::tableName(), [
            'id' => Schema::TYPE_PK,
            'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'campaignUserId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'parentCampaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'parentCampaignUserId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'amount' => Schema::TYPE_FLOAT. ' NOT NULL',
            /*these is name type in the form*/
            'nameFromForm' => Schema::TYPE_STRING. ' NOT NULL',
            /*these is names generally pulled from the paypal*/
            'firstName' => Schema::TYPE_STRING. ' NOT NULL',
            'lastName' => Schema::TYPE_STRING. ' NOT NULL',
            'email' => Schema::TYPE_STRING. ' NOT NULL',
            'comments' => Schema::TYPE_TEXT. ' NOT NULL',
            /*if user is logged during donations*/
            'userId' => Schema::TYPE_INTEGER,
            'createdDate' => Schema::TYPE_INTEGER. ' NOT NULL',
            'paymentDate' => Schema::TYPE_INTEGER. ' NOT NULL'
        ]);
        
        $this->createIndex('DONATION_CAMPAIGN_ID', Donation::tableName(), ['campaignId']);
        $this->createIndex('DONATION_PARENT_CAMPAIGN_ID', Donation::tableName(), ['parentCampaignId']);
    }


    public function down()
    {
        $this->dropTable(Donation::tableName());
    }
}
