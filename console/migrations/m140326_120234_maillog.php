<?php

use yii\db\Schema;
use common\models\mail\Mail;
class m140326_120234_maillog extends \console\models\ExtendedMigration
{
	public function up()
	{
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
	}

	public function down()
	{
            $this->dropTable(Mail::tableName());
	}
}
