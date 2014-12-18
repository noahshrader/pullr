<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\FeaturedCampaign;
use common\models\User;

class m141216_011502_add_featured_campaign_table extends Migration
{
    public function up()
    {
        $this->createTable(FeaturedCampaign::tableName(), [
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'campaignId' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);

        $this->addColumn(User::tableName(), 'enableFeaturedCampaign', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropTable(FeaturedCampaign::tableName());
        $this->dropColumn(User::tableName(), 'enableFeaturedCampaign');
    }
}
