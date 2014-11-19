<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetAlerts;
use frontend\models\streamboard\WidgetAlertsPreference;
class m141118_042743_widget_alert_text_alignment extends Migration
{
    public function up()
    {
    	$this->addColumn(WidgetAlerts::tableName(), 'messageWidth', Schema::TYPE_INTEGER . ' DEFAULT 150');
    	$this->addColumn(WidgetAlerts::tableName(), 'messageHeight', Schema::TYPE_INTEGER . ' DEFAULT 150');
    	$this->addColumn(WidgetAlertsPreference::tableName(), 'textAlignment', Schema::TYPE_STRING . ' NOT NULL DEFAULT "left"');
    }

    public function down()
    {
        $this->dropColumn(WidgetAlerts::tableName(), 'messageWidth');
        $this->dropColumn(WidgetAlerts::tableName(), 'messageHeight');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'textAlignment');   
    }
}
