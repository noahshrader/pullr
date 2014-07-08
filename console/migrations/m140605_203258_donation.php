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
        
        $this->sampleData();
    }

    public function sampleData(){
        $donations = [];
        $donations[] = [ 'nameFromForm' => 'Someone', 'email' => 'stas.msu@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Stanislav', 'email' => 'support@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Alex', 'email' => 'admin@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Dave'];
        $donations[] = [ 'nameFromForm' => 'Crash', 'email' => 'someone@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Noah', 'email' => 'noah@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Nikki', 'email' => 'nikki@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Yura', 'email' => 'yura@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Hedgehog', 'email' => 'hedgehog@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia2', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Maria', 'email' => 'maria@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'George', 'email' => 'george@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'campaignId' => 8, 'nameFromForm' => 'Sergey', 'email' => 'sergey@gmail.com'];
        $donations[] = [ 'campaignId' => 8, 'nameFromForm' => 'Sergey2', 'email' => 'sergey2@gmail.com'];
        $donations[] = [ 'campaignId' => 9, 'nameFromForm' => 'Sergey3', 'email' => 'sergey3@gmail.com'];
        $donations[] = [ 'campaignId' => 9, 'nameFromForm' => 'Sergey4', 'email' => 'sergey4@gmail.com'];
        
        foreach ($donations as $donationArray){
            $donation = new Donation();
            $donation->campaignId = isset($donationArray['campaignId'])? $donationArray['campaignId'] : 1;
            $donation->amount = round(rand(1, 1000));
            $donation->createdDate = time();
            $donation->paymentDate = time();
            $donation->nameFromForm = isset($donationArray['nameFromForm']) ? $donationArray['nameFromForm'] : '';
            $donation->lastName = $donation->nameFromForm;
            $donation->email = isset($donationArray['email']) ? $donationArray['email'] : '';
            $donation->comments = isset($donationArray['comments'])? $donationArray['comments'] : '';
            $donation->save();
        }
        
        \common\models\Campaign::updateDonationStatistics(1);
        \common\models\Campaign::updateDonationStatistics(8);
        \common\models\Campaign::updateDonationStatistics(9);
        \common\models\Campaign::updateDonationStatistics(Campaign::findOne(7)->parentCampaignId);
    }
    
    public function down()
    {
        $this->dropTable(Donation::tableName());
    }
}
