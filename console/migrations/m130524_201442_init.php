<?php

use common\models\User;
use common\models\OpenIDToUser;
use common\models\Notification;
use yii\db\Schema;

class m130524_201442_init extends \yii\db\Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(User::tableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'fullName' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . '(32)',
            'email' => Schema::TYPE_STRING,
            'photo' => Schema::TYPE_STRING,
            'smallPhoto' => Schema::TYPE_STRING,
            'birthday' => Schema::TYPE_DATE,
            'role' => Schema::TYPE_STRING . '(10) NOT NULL DEFAULT "user"',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'timezone' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);

        $this->createTable(OpenIDToUser::tableName(), [
            'userId' => Schema::TYPE_INTEGER . ' NOT NULL',
            'serviceName' => Schema::TYPE_STRING . ' NOT NULL',
            'serviceId' => Schema::TYPE_STRING . ' NOT NULL',
            'url' => Schema::TYPE_STRING
                ], $tableOptions);
        $this->addPrimaryKey('PRIMERY_KEY', OpenIDToUser::tableName(), ['serviceName', 'serviceId']);

        $this->createTable(Notification::tableName(), [
            'userId' => Schema::TYPE_PK,
            Notification::$NOTIFY_NEVER => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            Notification::$NOTIFY_DONATION_RECEIVED => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_NEW_FEATURE_ADDED => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_NEW_THEME_AVAILABLE => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_SYSTEM_UPDATE => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
                ], $tableOptions);
        
        
        $this->sampleUsers();
    }

    public function sampleUsers() {
        $user = new User();
        $user->setScenario('signup');
        $user->name = 'Stanislav';
        $user->password = 'Stanislav';
        $user->email = 'stas.msu@gmail.com';
        $user->save();

        $user = new User();
        $user->setScenario('openId');
        $user->name = 'Stanislav Klyukin';
        $user->email = 'stas.msu@gmail.com';
        $user->photo = 'https://plus.google.com/s2/photos/profile/116923822909679391954';
        $user->smallPhoto = 'https://plus.google.com/s2/photos/profile/116923822909679391954?sz=50';
        $user->save();

        $openId = new OpenIDToUser();
        $openId->userId = $user->id;
        $openId->serviceName = 'google_oauth';
        $openId->serviceId = '116923822909679391954';
        $openId->url = 'https://plus.google.com/+StanislavKlyukin';
        $openId->save();

        $user = new User();
        $user->setScenario('openId');
        $user->name = 'Stanislav Klyukin';
        $user->smallPhoto = 'http://pbs.twimg.com/profile_images/1410660514/a_e8c2fb55_normal.jpg';
        $user->save();

        $openId = new OpenIDToUser();
        $openId->userId = $user->id;
        $openId->serviceName = 'twitter';
        $openId->serviceId = '113944685';
        $openId->url = 'http://twitter.com/SKlyukin';
        $openId->save();


        $user = new User();
        $user->setScenario('signup');
        $user->name = 'Admin';
        $user->password = 'Admin';
        $user->save();

        $user->setScenario('roles');
        $user->role = User::ROLE_ADMIN;
        $user->save();
    }

    public function down() {
        $this->dropTable(OpenIDToUser::tableName());
        $this->dropTable('tbl_user');
    }

}
