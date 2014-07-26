<?php

use yii\db\Schema;
use common\models\twitch\TwitchUser;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchSubscription;
use common\models\user\UserFields;

class m140724_145144_twitch extends \console\models\ExtendedMigration
{
    const COLUMN_TWITCH_PARTNER = 'twitchPartner';
    const COLUMN_TWITCH_CHANNEL = 'twitchChannel';
    const COLUMN_TWITCH_ACCESS_TOKEN = 'twitchAccessToken';
    const COLUMN_TWITCH_ACCESS_TOKEN_DATE = 'twitchAccessTokenDate';

    public function up()
    {
        $this->addColumn(UserFields::tableName(), self::COLUMN_TWITCH_PARTNER, Schema::TYPE_BOOLEAN . ' NOT NULL');
        $this->addColumn(UserFields::tableName(), self::COLUMN_TWITCH_CHANNEL, Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(UserFields::tableName(), self::COLUMN_TWITCH_ACCESS_TOKEN, Schema::TYPE_STRING . ' NOT NULL');
        $this->addColumn(UserFields::tableName(), self::COLUMN_TWITCH_ACCESS_TOKEN_DATE, Schema::TYPE_INTEGER . ' NOT NULL');

        $this->createTable(TwitchUser::tableName(), [
            'userId' => Schema::TYPE_PK,
            'followersNumber' => Schema::TYPE_INTEGER . ' NOT NULL',
            'subscribersNumber' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updateDate' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        /*follows/ subscriptions begin*/
        $rows = [
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'twitchUserId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'createdAt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'display_name' => Schema::TYPE_STRING . ' NOT NULL',
            'jsonResponse' => Schema::TYPE_TEXT . ' NOT NULL',
            'updateDate' => Schema::TYPE_INTEGER . ' NOT NULL',
        ];

        $this->createTable(TwitchFollow::tableName(), $rows);
        $this->addPrimaryKey('twitch_follow_primary', TwitchFollow::tableName(), ['userId', 'twitchUserId']);
        $this->createIndex('twitch_follow_user_index', TwitchFollow::tableName(), ['userId']);

        $this->createTable(TwitchSubscription::tableName(), $rows);
        $this->addPrimaryKey('twitch_subscription_primary', TwitchSubscription::tableName(), ['userId', 'twitchUserId']);
        $this->createIndex('twitch_subscription_user_index', TwitchSubscription::tableName(), ['userId']);
        /*follows/subscriptions end*/
    }

    public function down()
    {
        $this->dropColumn(UserFields::tableName(), self::COLUMN_TWITCH_PARTNER);
        $this->dropColumn(UserFields::tableName(), self::COLUMN_TWITCH_CHANNEL);
        $this->dropColumn(UserFields::tableName(), self::COLUMN_TWITCH_ACCESS_TOKEN);
        $this->dropColumn(UserFields::tableName(), self::COLUMN_TWITCH_ACCESS_TOKEN_DATE);

        $this->dropTable(TwitchFollow::tableName());
        $this->dropTable(TwitchUser::tableName());
    }
}
