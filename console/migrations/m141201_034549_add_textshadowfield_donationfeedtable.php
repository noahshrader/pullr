<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetDonationFeed;

class m141201_034549_add_textshadowfield_donationfeedtable extends Migration
{
    const FIELD = 'textShadow';
    public function up()
    {
        $this->addColumn(WidgetDonationFeed::tableName(), self::FIELD, Schema::TYPE_BOOLEAN );
    }

    public function down()
    {
        $this->dropColumn(WidgetDonationFeed::tableName(), self::FIELD);
        return false;
    }
}
