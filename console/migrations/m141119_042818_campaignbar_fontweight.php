<?php

use yii\db\Schema;
use yii\db\Migration;

class m141119_042818_campaignbar_fontweight extends Migration
{
    const FIELD ='fontWeight';
    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetCampaignBar::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_INTEGER .' NOT NULL  DEFAULT 400 AFTER fontSize');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetCampaignBar::tableName(), self::FIELD);
        echo "Drop column ". self::FIELD ." success.\n";
    }
}
