<?php
namespace backend\controllers;

use common\components\ThemeScanner;
use common\models\Theme;

class ThemeController extends BackendController
{
	public function actionIndex()
	{   
            $status = isset($_POST['status']) ? $_POST['status'] : Theme::STATUS_ACTIVE;
            $params = [];
            $params['themes'] = Theme::find()->where(['status' => $status])->all();
            $params['status'] = $status;
            return $this->render('index', $params);
	}
        
        public function actionRescan(){
            $scanner = new ThemeScanner();
            $scanner->rescan();
            $this->redirect('index');
        }
}
