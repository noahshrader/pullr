<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetAlerts;
use frontend\models\streamboard\WidgetDonationFeed;
use frontend\models\streamboard\StreamboardConfig;
class m141102_040518_add_position_and_size_column extends Migration
{
    public function up()
    {    	    
    	$this->addColumn(WidgetAlerts::tableName(), 'messagePositionX', Schema::TYPE_INTEGER);
    	$this->addColumn(WidgetAlerts::tableName(), 'messagePositionY', Schema::TYPE_INTEGER);
        $this->addColumn(WidgetAlerts::tableName(), 'imageWidth', Schema::TYPE_INTEGER);
        $this->addColumn(WidgetAlerts::tableName(), 'imageHeight', Schema::TYPE_INTEGER);
        $this->addColumn(WidgetAlerts::tableName(), 'imagePositionX', Schema::TYPE_INTEGER);
        $this->addColumn(WidgetAlerts::tableName(), 'imagePositionY', Schema::TYPE_INTEGER);

        $this->addColumn(WidgetDonationFeed::tableName(), 'width', Schema::TYPE_INTEGER);
        $this->addColumn(WidgetDonationFeed::tableName(), 'height', Schema::TYPE_INTEGER);

        $this->addColumn(StreamboardConfig::tableName(), 'sidePanelWidth', Schema::TYPE_INTEGER);
        $this->addColumn(StreamboardConfig::tableName(), 'region2HeightPercent', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        echo "m141102_040518_add_position_and_size_column cannot be reverted.\n";
        return false;
    }
}
