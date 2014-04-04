<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Theme;
use common\components\ThemeScanner;

class ThemeController extends Controller {
    public function actionRescan(){
        $scanner = new ThemeScanner();
        $scanner->rescan();
    }
}
