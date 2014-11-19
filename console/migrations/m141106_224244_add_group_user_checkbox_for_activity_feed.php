<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetDonationFeed;
class m141106_224244_add_group_user_checkbox_for_activity_feed extends Migration
{
    public function up()
    {
    	$this->addColumn(WidgetDonationFeed::tableName(), 'groupUser', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    }

    public function down()
    {
        echo "m141106_224244_add_group_user_checkbox_for_activity_feed cannot be reverted.\n";

        return false;
    }
}
