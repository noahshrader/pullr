<?php

use yii\db\Schema;
use yii\db\Migration;

class m141023_185003_new_field_enableDonationProgressBar extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Campaign::tableName(), 'enableDonationProgressBar', Schema::TYPE_BOOLEAN . " DEFAULT 1 AFTER enableThankYouPage");

    }

    public function down()
    {
        $this->dropColumn(\common\models\Campaign::tableName(), 'enableDonationProgressBar');
    }
}
