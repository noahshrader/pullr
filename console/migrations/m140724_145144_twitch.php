<?php

use yii\db\Schema;
use common\models\twitch\TwitchUser;
use common\models\twitch\TwitchFollow;

class m140724_145144_twitch extends \console\models\ExtendedMigration
{
    public function up()
    {
        $this->createTable(TwitchUser::tableName(), [
            'userId' => Schema::TYPE_PK,
            'followersNumber' => Schema::TYPE_INTEGER . ' NOT NULL',
            'subscribersNumber' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updateDate' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->createTable(TwitchFollow::tableName(), [
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'twitchFollowerId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'createdAt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'display_name' => Schema::TYPE_STRING . ' NOT NULL',
            'jsonResponse' => Schema::TYPE_TEXT . ' NOT NULL',
            'updateDate' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->addPrimaryKey('twitch_follow_primary',TwitchFollow::tableName(),['userId', 'twitchFollowerId']);
        $this->createIndex('twitch_follow_user_index',TwitchFollow::tableName(),['userId']);
    }

    public function down()
    {
        $this->dropTable(TwitchFollow::tableName());
        $this->dropTable(TwitchUser::tableName());
    }
}
