<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetTags;
use frontend\models\streamboard\WidgetTagStyle;

class m141119_065538_add_position_and_size_column extends Migration
{

    public function up()
    {

        $this->addColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontStyle', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontSize', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontWeight', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontColor', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontUppercase', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');

        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'fontStyle');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'fontSize');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'fontWeight');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'fontColor');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'fontUppercase');

        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'tagType', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'height', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'width', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionX', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');
        $this->addColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionY', \yii\db\mysql\Schema::TYPE_INTEGER . ' NOT NULL');

        $this->addPrimaryKey('streamboard_widget_alerts', WidgetTagStyle::tableName(), ['userId', 'regionNumber','tagType']);
    }

    public function down()
    {


        $this->dropColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontStyle');
        $this->dropColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontSize');
        $this->dropColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontWeight');
        $this->dropColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontColor');
        $this->dropColumn(\frontend\models\streamboard\WidgetTags::tableName(), 'fontUppercase');

        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'tagType');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'height');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'width');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionX');
        $this->dropColumn(\frontend\models\streamboard\WidgetTagStyle::tableName(), 'messagePositionY');
        return false;
    }
}
