<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlertsPreference;


class m141126_020901_add_alert_position extends Migration
{
    public function up()
    {
        $this->addColumn(WidgetAlertsPreference::tableName(), 'messageWidth', Schema::TYPE_INTEGER . ' NOT NULL  DEFAULT 150');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'messageHeight', Schema::TYPE_INTEGER . ' NOT NULL  DEFAULT 150');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'messagePositionX', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'messagePositionY', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'imageWidth', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'imageHeight', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'imagePositionX', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlertsPreference::tableName(), 'imagePositionY', Schema::TYPE_INTEGER . ' NOT NULL');

    }

    public function down()
    {
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'messageWidth');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'messageHeight');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'messagePositionX');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'messagePositionY');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'imageWidth');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'imageHeight');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'imagePositionX');
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'imagePositionY');
        return false;
    }
}
