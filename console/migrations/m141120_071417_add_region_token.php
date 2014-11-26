<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardConfig;
use common\models\User;
use yii\base\Security;
class m141120_071417_add_region_token extends Migration
{
    public function up()
    {
    	$this->addColumn(StreamboardConfig::tableName(), 'streamboardToken', Schema::TYPE_STRING . ' NOT NULL');    	

        $users = User::find()->all();
        foreach ($users as $user) {
            $user->streamboardConfig->createRegionToken();            
            $user->streamboardConfig->save();
        }

    }

    public function down()
    {
        $this->dropColumn(StreamboardConfig::tableName(), 'streamboardToken');        
    }
}
