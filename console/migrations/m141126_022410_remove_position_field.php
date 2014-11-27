<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlerts;


class m141126_022410_remove_position_field extends Migration
{
    public function up()
    {
        $this->dropColumn(WidgetAlerts::tableName(), 'messageWidth');
        $this->dropColumn(WidgetAlerts::tableName(), 'messageHeight');
        $this->dropColumn(WidgetAlerts::tableName(), 'messagePositionX');
        $this->dropColumn(WidgetAlerts::tableName(), 'messagePositionY');
        $this->dropColumn(WidgetAlerts::tableName(), 'imageWidth');
        $this->dropColumn(WidgetAlerts::tableName(), 'imageHeight');
        $this->dropColumn(WidgetAlerts::tableName(), 'imagePositionX');
        $this->dropColumn(WidgetAlerts::tableName(), 'imagePositionY');
    }

    public function down()
    {
        $this->addColumn(WidgetAlerts::tableName(), 'messageWidth', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'messageHeight', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'messagePositionX', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'messagePositionY', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'imageWidth', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'imageHeight', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'imagePositionX', Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(WidgetAlerts::tableName(), 'imagePositionY', Schema::TYPE_INTEGER . ' NOT NULL');
        return false;
    }
}
