<?php

use yii\db\Schema;
use console\models\ExtendedMigration;
use frontend\models\streamboard\StreamboardConfig;
use common\models\twitch\TwitchSubscription;
use common\models\twitch\TwitchFollow;

class m140827_155952_streamboard_stream_fields extends ExtendedMigration
{
    public function up()
    {
        $this->addColumn(StreamboardConfig::tableName(), 'streamRequestLastDate', Schema::TYPE_INTEGER.' NOT NULL');
        $this->addColumn(TwitchSubscription::tableName(), 'createdAtPullr', Schema::TYPE_INTEGER.' NOT NULL');
        $this->addColumn(TwitchFollow::tableName(), 'createdAtPullr', Schema::TYPE_INTEGER.' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn(StreamboardConfig::tableName(), 'streamRequestLastDate');
        $this->dropColumn(TwitchSubscription::tableName(), 'createdAtPullr');
        $this->dropColumn(TwitchFollow::tableName(), 'createdAtPullr');
    }
}
