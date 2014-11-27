<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBarAlerts;

class m141112_102031_custom_alertfield_add extends Migration
{
    const FIELD = 'alertText';
    public function up()
    {
    	$this->addColumn(frontend\models\streamboard\WidgetAlertsPreference::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
        $this->addColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(frontend\models\streamboard\WidgetAlertsPreference::tableName(), self::FIELD);
        $this->dropColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), self::FIELD);
        echo "m141112_102031_custom_alertfield_add cannot be reverted.\n";
    }
}
