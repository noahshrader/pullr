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
            /*these is name type in the form*/
            'nameFromForm' => Schema::TYPE_STRING. ' NOT NULL',
            /*these is names generally pulled from the paypal*/
            'firstName' => Schema::TYPE_STRING. ' NOT NULL',
            'lastName' => Schema::TYPE_STRING. ' NOT NULL',
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
        $users[] = [ 'nameFromForm' => 'Someone', 'email' => 'stas.msu@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Stanislav', 'email' => 'support@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Alex', 'email' => 'admin@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Dave'];
        $users[] = [ 'nameFromForm' => 'Crash', 'email' => 'someone@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Noah', 'email' => 'noah@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Nikki', 'email' => 'nikki@gmail.com', 'comments' => 'just some comments to test donations'];
        $users[] = [ 'nameFromForm' => 'Yura', 'email' => 'yura@gmail.com'];
        $users[] = [ 'nameFromForm' => 'Hedgehog', 'email' => 'hedgehog@gmail.com'];
        $users[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $users[] = [ 'nameFromForm' => 'Sofia2', 'email' => 'sofia@gmail.com'];
        $users[] = [ 'nameFromForm' => 'Maria', 'email' => 'maria@gmail.com'];
        $users[] = [ 'nameFromForm' => 'George', 'email' => 'george@gmail.com'];
        $users[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        
        foreach ($users as $user){
            $donation = new Donation();
            $donation->campaignId = 1;
            $donation->userId = 1;
            $donation->amount = round(rand(1, 1000));
            $donation->createdDate = time();
            $donation->paymentDate = time();
            $donation->nameFromForm = isset($user['nameFromForm']) ? $user['nameFromForm'] : '';
            $donation->lastName = $donation->nameFromForm;
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
