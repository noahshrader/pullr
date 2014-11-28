<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetTags;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetTagStyle;

class m141128_054913_creat_tag_date_for_exitinguser extends Migration
{
    public function up()
    {
        WidgetTags::deleteAll();
        WidgetTagStyle::deleteAll();

        $rows = StreamboardRegion::find()->all();
        foreach($rows as $row){
            $widgetTagRow = new WidgetTags();
            $widgetTagRow->userId = $row->userId;
            $widgetTagRow->regionNumber = $row->regionNumber;
            $widgetTagRow->save();
        }
    }

    public function down()
    {
        return false;
    }
}
