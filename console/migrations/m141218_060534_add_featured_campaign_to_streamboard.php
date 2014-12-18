<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardConfig;
class m141218_060534_add_featured_campaign_to_streamboard extends Migration
{
    public function up()
    {
        $this->addColumn(StreamboardConfig::tableName(), 'enableFeaturedCampaign', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
        $this->addColumn(StreamboardConfig::tableName(), 'featuredCampaignId', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn(StreamboardConfig::tableName(), 'enableFeaturedCampaign');
        $this->dropColumn(StreamboardConfig::tableName(), 'featuredCampaignId');
    }
}
