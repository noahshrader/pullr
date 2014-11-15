<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\WidgetCampaignBarAlerts;

class m141113_093652_campaginbar_custom_alert extends Migration
{


    public function up()
    {
        $this->dropColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'alertText');
        $this->addColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'followerText', \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
        $this->addColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'subscriberText', \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
        $this->addColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'donationText', \yii\db\mysql\Schema::TYPE_TEXT . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'followerText');
        $this->dropColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'subscriberText');
        $this->dropColumn(frontend\models\streamboard\WidgetCampaignBarAlerts::tableName(), 'donationText');      
        return false;
    }
}
