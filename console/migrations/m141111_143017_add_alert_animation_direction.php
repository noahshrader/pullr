<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetAlertsPreference;

class m141111_143017_add_alert_animation_direction extends Migration
{
    public function up()
    {
    	$this->addColumn(WidgetAlertsPreference::tableName(), 'animationDirection', Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m141111_143017_add_alert_animation_direction cannot be reverted.\n";

        return false;
    }
}
