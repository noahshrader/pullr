<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBar;
use frontend\models\streamboard\WidgetCampaignBarAlerts;
use frontend\models\streamboard\WidgetTags;

class m141128_040335_add_textshadow_filed extends Migration
{
    const FIELD = 'textShadow';

    public function up()
    {
        $this->addColumn(WidgetAlertsPreference::tableName(), self::FIELD, Schema::TYPE_BOOLEAN);
        $this->addColumn(WidgetCampaignBar::tableName(), self::FIELD, Schema::TYPE_BOOLEAN);
        $this->addColumn(WidgetCampaignBarAlerts::tableName(), self::FIELD, Schema::TYPE_BOOLEAN);
        $this->addColumn(WidgetTags::tableName(), self::FIELD, Schema::TYPE_BOOLEAN );
    }

    public function down()
    {

        $this->dropColumn(WidgetAlertsPreference::tableName(), self::FIELD);
        $this->dropColumn(WidgetCampaignBar::tableName(), self::FIELD);
        $this->dropColumn(WidgetCampaignBarAlerts::tableName(), self::FIELD);
        $this->dropColumn(WidgetTags::tableName(), self::FIELD);
    }
}
