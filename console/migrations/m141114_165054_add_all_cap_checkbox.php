<?php

use yii\db\Schema;
use yii\db\Migration;
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetAlerts;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBar;
use frontend\models\streamboard\WidgetCampaignBarAlerts;
use frontend\models\streamboard\WidgetCampaignBarMessages;
use frontend\models\streamboard\WidgetCampaignBarTimer;
use frontend\models\streamboard\WidgetCampaignBarCurrentTotal;
use frontend\models\streamboard\WidgetDonationFeed;

class m141114_165054_add_all_cap_checkbox extends Migration
{
    public function up()
    {    
    	$this->addColumn(WidgetAlertsPreference::tableName(), 'fontUppercase', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    	$this->addColumn(WidgetCampaignBar::tableName(), 'fontUppercase', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    	$this->addColumn(WidgetCampaignBarAlerts::tableName(), 'fontUppercase', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    	$this->addColumn(WidgetDonationFeed::tableName(), 'fontUppercase', Schema::TYPE_BOOLEAN . ' DEFAULT 0');
    }

    public function down()
    {     
        $this->dropColumn(WidgetAlertsPreference::tableName(), 'fontUppercase');
        $this->dropColumn(WidgetCampaignBar::tableName(), 'fontUppercase');
        $this->dropColumn(WidgetCampaignBarAlerts::tableName(), 'fontUppercase');
        $this->dropColumn(WidgetDonationFeed::tableName(), 'fontUppercase');
        return false;
    }
}
