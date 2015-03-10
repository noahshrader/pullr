<?php

use yii\db\Schema;
use yii\db\Migration;

class m150309_022435_activity_sort_filter extends Migration
{
    public function up()
    {
      $this->addColumn('tbl_streamboard_widget_donation_feed', 'sortBy', 'enum("Amount", "Time", "Alphabet") default "amount"');
    }

    public function down()
    {
      $this->dropColumn('tbl_streamboard_widget_donation_feed', 'sortBy');
    }
}
