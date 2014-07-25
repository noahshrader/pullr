<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class PullrController extends Controller
{
    public function actionPurge()
    {
        $sql = <<<"SQL"
SET FOREIGN_KEY_CHECKS = 0;
SET GROUP_CONCAT_MAX_LEN=32768;
SET @tables = NULL;
SELECT GROUP_CONCAT(table_name) INTO @tables
  FROM information_schema.tables
  WHERE table_schema = (SELECT DATABASE());
SELECT IFNULL(@tables,'dummy') INTO @tables;

SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables);
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET FOREIGN_KEY_CHECKS = 1;
SQL;

        echo "Preparing to table removing\n";
        \Yii::$app->db->createCommand($sql)->execute();
        echo "Tables have been removed\n\n";
    }

    public function actionSample(){
        $ctrl = new Sample_dataController($this->id, $this->module);
        $ctrl->actionIndex();

        $ctrl = new ThemeController($this->id, $this->module);
        $ctrl->actionRescan();
    }
}
