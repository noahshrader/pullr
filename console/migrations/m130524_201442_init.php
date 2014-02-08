<?php
use common\models\User;
use yii\db\Schema;

class m130524_201442_init extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable(User::tableName(), [
			'id' => Schema::TYPE_PK,
			'name' => Schema::TYPE_STRING . ' NOT NULL',
			'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
			'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
			'password_reset_token' => Schema::TYPE_STRING . '(32)',
			'email' => Schema::TYPE_STRING,
                        'photo' => Schema::TYPE_STRING,
                        'smallPhoto' => Schema::TYPE_STRING,
                        'birthday' => Schema::TYPE_DATE,
			'role' => Schema::TYPE_STRING . '(10) NOT NULL DEFAULT "user"',
			'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
			'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
			'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
		], $tableOptions);
	}

	public function down()
	{
		$this->dropTable('tbl_user');
	}
}
