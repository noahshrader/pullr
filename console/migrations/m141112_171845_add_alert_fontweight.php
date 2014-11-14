<?php

use yii\db\Schema;
use yii\db\Migration;

use frontend\models\streamboard\WidgetAlertsPreference;

class m141112_171845_add_alert_fontweight extends Migration
{
    const FIELD = 'fontWeight';
    
    public function up()
    {
        $this->addColumn(WidgetAlertsPreference::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_INTEGER .' NOT NULL  DEFAULT 400 AFTER fontSize');
    }

    public function down()
    {
        $this->dropColumn(WidgetAlertsPreference::tableName(), self::FIELD);
        echo "m141112_171845_add_alert_fontweight cannot be reverted.\n";

        return false;
    }
}
