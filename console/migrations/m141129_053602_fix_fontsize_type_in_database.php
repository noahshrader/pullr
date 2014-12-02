<?php

use yii\db\Schema;
use yii\db\Migration;

use frontend\models\streamboard\WidgetDonationFeed;
use frontend\models\streamboard\WidgetTags;
use frontend\models\streamboard\WidgetCampaignBar;

class m141129_053602_fix_fontsize_type_in_database extends Migration
{
    public function up()
    {
    	$this->alterColumn(WidgetDonationFeed::tableName(), 'fontSize', Schema::TYPE_INTEGER);
    	$this->alterColumn(WidgetTags::tableName(), 'fontSize', Schema::TYPE_INTEGER);
    	$this->alterColumn(WidgetTags::tableName(), 'fontWeight', Schema::TYPE_INTEGER);
    	$this->alterColumn(WidgetCampaignBar::tableName(), 'fontSize', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->alterColumn(WidgetDonationFeed::tableName(), 'fontSize', Schema::TYPE_STRING);
    	$this->alterColumn(WidgetTags::tableName(), 'fontSize', Schema::TYPE_STRING);
    	$this->alterColumn(WidgetTags::tableName(), 'fontWeight', Schema::TYPE_STRING);
    	$this->alterColumn(WidgetCampaignBar::tableName(), 'fontSize', Schema::TYPE_STRING);
    }
}
