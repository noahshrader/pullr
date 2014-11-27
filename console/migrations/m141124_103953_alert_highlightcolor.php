<?php

use yii\db\Schema;
use yii\db\Migration;

class m141124_103953_alert_highlightcolor extends Migration
{
    const FIELD = 'highlightColor';
    
    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetAlertsPreference::tableName(), self::FIELD, yii\db\mysql\Schema::TYPE_STRING .' AFTER fontColor');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetAlertsPreference::tableName(), self::FIELD);
        echo "Drop column ". self::FIELD ." success.\n";

        return false;
    }
}
