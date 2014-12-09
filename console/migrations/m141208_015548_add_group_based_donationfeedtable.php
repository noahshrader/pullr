<?php

use yii\db\Schema;
use yii\db\Migration;

class m141208_015548_add_group_based_donationfeedtable extends Migration
{
    const FIELD ='groupBase';
    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetDonationFeed::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_STRING .' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetDonationFeed::tableName(), self::FIELD);
    }
}
