<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardConfig;
use frontend\models\streamboard\StreamboardCampaign;

class m140314_095651_streamboard extends ExtendedMigration
{
    public function up()
    {
        $this->createTable(StreamboardConfig::tableName(), [
           'userId' => Schema::TYPE_PK,
            'streamboardWidth' => Schema::TYPE_INTEGER,
            'streamboardHeight' => Schema::TYPE_INTEGER
        ]);

        $this->createTable(StreamboardCampaign::tableName(), [
            'campaignId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'selected' => Schema::TYPE_BOOLEAN. ' DEFAULT TRUE NOT NULL'
        ]);

        $this->addPrimaryKey('streamboard_campaigns_primary', StreamboardCampaign::tableName(), ['campaignId', 'userId']);
    }

    public function down()
    {
        $this->dropTable(StreamboardCampaign::tableName());
        $this->dropTable(StreamboardConfig::tableName());
    }
}
