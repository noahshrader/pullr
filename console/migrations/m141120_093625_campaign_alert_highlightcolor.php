<?php

use yii\db\Schema;
use yii\db\Migration;

class m141120_093625_campaign_alert_highlightcolor extends Migration
{
    const FIELD = 'highlightColor';
    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), self::FIELD, yii\db\mysql\Schema::TYPE_STRING .' AFTER fontColor');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), self::FIELD);
        echo "Drop column ". self::FIELD ." success.\n";
    }
}
