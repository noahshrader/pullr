<?php

use yii\db\Schema;
use common\models\Theme;
use common\models\Layout;
use common\models\Plan;

class m140404_064916_theme extends \console\models\ExtendedMigration
{
    public function up()
    {
        $statuses = implode('","', Theme::$STATUSES);
        $statuses = "ENUM (\"$statuses\") NOT NULL DEFAULT \"" . Theme::STATUS_ACTIVE . '"';
        
        $layoutTypes = implode('","', Layout::$TYPES);
        $layoutTypes = "ENUM (\"$layoutTypes\") NOT NULL";

        $plans = implode('","', Plan::$PLANS);
        $plans = "ENUM (\"$plans\") NOT NULL";
            
        $this->createTable(Theme::tableName(), [
            'id' => Schema::TYPE_PK,
            'filename' => Schema::TYPE_STRING. ' NOT NULL',
            'status' => $statuses,
            'name' => Schema::TYPE_STRING. ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'layoutType' => $layoutTypes,
            'plan' => $plans
        ]);
    }

    public function down()
    {
        $this->dropTable(Theme::tableName());
    }
}
