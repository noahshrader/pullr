<?php

use common\models\User;
use common\models\OpenIDToUser;
use common\models\Notification;
use common\models\Plan;
use common\models\PlanHistory;
use frontend\models\site\DeactivateAccount;
use yii\db\Schema;
use frontend\models\site\EmailConfirmation;
use common\models\mail\Mail;
use common\models\user\UserFields;

class m130524_201442_init extends \console\models\ExtendedMigration{

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $statuses = implode('","', User::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . User::STATUS_ACTIVE . '"';
        
        $this->createTable(User::tableName(), [
            'id' => Schema::TYPE_PK,
            'login' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'uniqueName' => Schema::TYPE_STRING. ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING,
            'photoId' => Schema::TYPE_INTEGER,
            'photo' => Schema::TYPE_STRING,
            'smallPhoto' => Schema::TYPE_STRING,
            'birthday' => Schema::TYPE_DATE,
            'role' => Schema::TYPE_STRING . '(20) NOT NULL DEFAULT "user"',
            'status' => $statuses,
            'timezone' => Schema::TYPE_STRING . ' NOT NULL',
            'last_login' => Schema::TYPE_INTEGER. ' NOT NULL',
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
        
        $this->createTable(UserFields::tableName(), [
            'userId' => Schema::TYPE_PK,
            'systemNotificationDate' => Schema::TYPE_INTEGER
        ]);
        
        $this->createTable(Notification::tableName(), [
            'userId' => Schema::TYPE_PK,
            Notification::$NOTIFY_NEVER => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            Notification::$NOTIFY_DONATION_RECEIVED => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_NEW_FEATURE_ADDED => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_NEW_THEME_AVAILABLE => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
            Notification::$NOTIFY_SYSTEM_UPDATE => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
                ], $tableOptions);
        
        $plans = implode('","', Plan::$PLANS);
        $plans = "ENUM (\"$plans\") NOT NULL DEFAULT \"" . Plan::PLAN_BASE . '"';
        
        $this->createTable(Plan::tableName(), [
            'id' => Schema::TYPE_PK,
            'plan' => $plans, 
            'expire' => Schema::TYPE_INTEGER . ' NOT NULL', 
            'subscription' => Schema::TYPE_STRING . '(20)',
        ]);
        
        $this->createTable(PlanHistory::tableName(), [
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'plan' => Schema::TYPE_STRING. ' NOT NULL',
            'days' => Schema::TYPE_FLOAT. ' NOT NULL',
            'date' => Schema::TYPE_INTEGER. ' NOT NULL',
        ]);
        $this->createIndex('planHistoryUserId', PlanHistory::tableName(), ['userId']);
        
        $statuses = implode('","', EmailConfirmation::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . EmailConfirmation::STATUS_SENT . '"';
        
        $this->createTable(EmailConfirmation::tableName(), [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING. ' NOT NULL',
            'key' => Schema::TYPE_STRING. ' NOT NULL',
            'status' => $statuses,
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'lastSent' => Schema::TYPE_INTEGER. ' NOT NULL'
        ]);
        $this->createIndex('emailConfirmationEmail', EmailConfirmation::tableName(), ['email']);
        $this->createIndex('emailConfirmationUserId', EmailConfirmation::tableName(), ['userId']);
        
        $this->createTable(DeactivateAccount::tableName(), [
            'userId' => Schema::TYPE_INTEGER. ' NOT NULL',
            'reason' => Schema::TYPE_TEXT,
            'creationDate' => Schema::TYPE_INTEGER,
            'processingDate' => Schema::TYPE_INTEGER
        ]);
        $this->createIndex('deactivateUserId', DeactivateAccount::tableName(), ['userId']);
        
         $this->createTable(Mail::tableName(), [
                 'id' => Schema::TYPE_PK,
                 'from' => Schema::TYPE_STRING. ' NOT NULL',
                 'to' => Schema::TYPE_STRING. ' NOT NULL',
                 'subject' => Schema::TYPE_STRING. ' NOT NULL',
                 'text' => Schema::TYPE_TEXT. ' NOT NULL',
                 'type' => Schema::TYPE_STRING,
                 'creationDate' => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
                 'processingDate' => Schema::TYPE_INTEGER
             ]);
         
        $this->sampleUsers();
    }

    public function sampleUsers() {
        $user = new User();
        $user->setScenario('signup');
        $user->login = 'stanislav@gmail.com';
        $user->name = 'Stanislav';
        $user->password = 'Stanislav';
        $user->confirmPassword = $user->password;
        $user->email = 'stas.msu@gmail.com';
        $user->save();
        
        $user = new User();
        $user->setScenario('signup');
        $user->login = 's.klyukin@yandex.ru';
        $user->name = 'Stanislav';
        $user->password = 'Stanislav';
        $user->confirmPassword = $user->password;
        $user->email = 's.klyukin@yandex.ru';
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
        $user->setScenario('openId');
        $user->name = 'Klyukin';
        $user->uniqueName = 'klyukin';
        $user->photo = 'http://static-cdn.jtvnw.net/jtv_user_pictures/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg';
        $user->smallPhoto = 'http://static-cdn.jtvnw.net/jtv_user_pictures/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg';
        $user->save();

        $openId = new OpenIDToUser();
        $openId->userId = $user->id;
        $openId->serviceName = 'twitch';
        $openId->serviceId = '55052982';
        $openId->url = 'http://twitch.tv/klyukin';
        $openId->save();


        $user = new User();
        $user->setScenario('signup');
        $user->login = 'admin@gmail.com';
        $user->name = 'Admin';
        $user->password = 'Admin';
        $user->email = 'pullr@yandex.com';
        $user->save();
        
        $user->setScenario('roles');
        $user->role = User::ROLE_ADMIN;
        $user->save();
    }

    public function down() {
        $this->dropTable(OpenIDToUser::tableName());
        $this->dropTable(User::tableName());
        $this->dropTable(Notification::tableName());
        $this->dropTable(Plan::tableName());
        $this->dropTable(PlanHistory::tableName());
        $this->dropTable(DeactivateAccount::tableName());
        $this->dropTable(EmailConfirmation::tableName());
        $this->dropTable(UserFields::tableName());
        $this->dropTable(Mail::tableName());
    }

}
