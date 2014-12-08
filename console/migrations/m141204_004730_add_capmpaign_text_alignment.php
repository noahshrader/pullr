<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetCampaignBarAlerts;

class m141204_004730_add_capmpaign_text_alignment extends Migration
{
    public function up()
    {
        $this->addColumn(WidgetCampaignBarAlerts::tableName(), 'messageWidth', Schema::TYPE_INTEGER . '');
        $this->addColumn(WidgetCampaignBarAlerts::tableName(), 'messageHeight', Schema::TYPE_INTEGER . '');
        $this->addColumn(WidgetCampaignBarAlerts::tableName(), 'textAlignment', Schema::TYPE_STRING . ' NOT NULL DEFAULT "left"');
    }

    public function down()
    {
        $this->dropColumn(WidgetCampaignBarAlerts::tableName(), 'messageWidth');
        $this->dropColumn(WidgetCampaignBarAlerts::tableName(), 'messageHeight');
        $this->dropColumn(WidgetCampaignBarAlerts::tableName(), 'textAlignment');
    }
}
