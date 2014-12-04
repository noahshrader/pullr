<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetCampaignBarMessages;

class m141204_024801_add_rotate_message_resize extends Migration
{
    public function up()
    {
        $this->addColumn(WidgetCampaignBarMessages::tableName(), 'messageWidth', Schema::TYPE_INTEGER . '');
        $this->addColumn(WidgetCampaignBarMessages::tableName(), 'messageHeight', Schema::TYPE_INTEGER . '');
    }

    public function down()
    {
        $this->dropColumn(WidgetCampaignBarMessages::tableName(), 'messageWidth');
        $this->dropColumn(WidgetCampaignBarMessages::tableName(), 'messageHeight');
    }
}
