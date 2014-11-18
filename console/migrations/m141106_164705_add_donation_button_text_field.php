<?php

use yii\db\Schema;
use yii\db\Migration;

class m141106_164705_add_donation_button_text_field extends Migration
{
    const FIELD = 'donationButtonText';

    public function up()
    {
        $this->addColumn(\common\models\Campaign::tableName(), self::FIELD, \yii\db\mysql\Schema::TYPE_STRING . ' AFTER thankYouPageText');
    }

    public function down()
    {
        $this->dropColumn(\common\models\Campaign::tableName(), self::FIELD);
    }
}
