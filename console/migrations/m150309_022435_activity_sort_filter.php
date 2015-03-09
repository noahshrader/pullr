<?php

use yii\db\Schema;
use yii\db\Migration;

class m150309_022435_activity_sort_filter extends Migration
{
    public function up()
    {
      $this->addColumn('tbl_streamboard_widget_donation_feed', 'sortBy', 'enum("amount", "time", "alphabet") default "amount"');
    }

    public function down()
    {
        echo "m150309_022435_activity_sort_filter cannot be reverted.\n";

        return false;
    }
}
