<?php

use yii\db\Schema;
use yii\db\Migration;

class m141114_050533_add_donationfeed_fontweight extends Migration
{
    const FIELD = 'fontWeight';
    
    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetDonationFeed::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_INTEGER .' NOT NULL  DEFAULT 400 AFTER fontSize');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetDonationFeed::tableName(), self::FIELD);
        echo "m141114_050533_add_donationfeed_fontweight cannot be reverted.\n";

        return false;
    }
}
