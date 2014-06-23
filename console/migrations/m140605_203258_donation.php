<?php

use yii\db\Schema;
use common\models\Donation;

class m140605_203258_donation extends \console\models\ExtendedMigration
{
    public function up()
    {
        $this->createTable(Donation::tableName(), [
            'id' => Schema::TYPE_PK,
            'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER,
            'amount' => Schema::TYPE_FLOAT. ' NOT NULL',
            'name' => Schema::TYPE_STRING. ' NOT NULL',
            'email' => Schema::TYPE_STRING. ' NOT NULL',
            'comments' => Schema::TYPE_TEXT. ' NOT NULL',
            'createdDate' => Schema::TYPE_INTEGER. ' NOT NULL',
            'paymentDate' => Schema::TYPE_INTEGER. ' NOT NULL'
        ]);
        
        $this->createIndex('DONATION_CAMPAIGN_ID', Donation::tableName(), ['campaignId']);
        
        $this->sampleData();
    }

    public function sampleData(){
        $users = [];
        $users[] = [ 'name' => 'Someone', 'email' => 'stas.msu@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Stanislav', 'email' => 'support@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Alex', 'email' => 'admin@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Dave'];
        $users[] = [ 'name' => 'Crash', 'email' => 'someone@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Noah', 'email' => 'noah@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Nikki', 'email' => 'nikki@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'name' => 'Yura', 'email' => 'yura@gmail.com'];
        $users[] = [ 'name' => 'Hedgehog', 'email' => 'hedgehog@gmail.com'];
        $users[] = [ 'name' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $users[] = [ 'name' => 'Maria', 'email' => 'maria@gmail.com'];
        $users[] = [ 'name' => 'George', 'email' => 'george@gmail.com'];
        
        foreach ($users as $user){
            $donation = new Donation();
            $donation->campaignId = 1;
            $donation->userId = 1;
            $donation->amount = round(rand(1, 1000));
            $donation->createdDate = time();
            $donation->paymentDate = time();
            $donation->name = isset($user['name']) ? $user['name'] : '';
            $donation->email = isset($user['email']) ? $user['email'] : '';
            $donation->comments = isset($user['comments'])? $user['comments'] : '';
            $donation->save();
        }
        
        \common\models\Campaign::updateDonationStatistics(1);
    }
    
    public function down()
    {
        $this->dropTable(Donation::tableName());
    }
}
