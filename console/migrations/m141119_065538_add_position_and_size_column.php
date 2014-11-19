<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetTagStyle;

class m141119_065538_add_position_and_size_column extends Migration
{

    public function up()
    {
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'height', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'width', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionX', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionY', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'height');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'width');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionX');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionY');
        return false;
    }
}
