<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\theme\ThemeScanner;

class ThemeController extends Controller {
    public function actionRescan(){
        echo "Starting theme rescan\n";
        $scanner = new ThemeScanner();
        $scanner->rescan();
        echo "Themes were rescaned\n";
        
        echo "Updating theme default value\n";
        
        \Yii::$app->db->createCommand('UPDATE '. \common\models\Theme::tableName() .' SET is_default = 1 WHERE `filename` = \'bdmulti-1\' ')->execute();
        \Yii::$app->db->createCommand('UPDATE '. \common\models\Theme::tableName() .' SET is_default = 1 WHERE `filename` = \'bdsingle\' ')->execute();
        \Yii::$app->db->createCommand('UPDATE '. \common\models\Theme::tableName() .' SET is_default = 1 WHERE `filename` = \'bdteam-1\' ')->execute();
        
        echo "Updated theme default value\n";
    }
}
