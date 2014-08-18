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
    }
}
