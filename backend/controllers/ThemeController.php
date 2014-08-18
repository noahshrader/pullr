<?php
namespace backend\controllers;

use common\components\theme\ThemeScanner;
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

    public function actionRescan()
    {
        $scanner = new ThemeScanner();
        $scanner->rescan();
        $this->redirect('index');
    }

    public function actionDisable()
    {
        $theme = Theme::findOne(intval($_GET['themeId']));
        $theme->status='removed';
        $theme->save();
        $this->redirect('index');
    }

    public function actionEnable()
    {
        $theme = Theme::findOne(intval($_GET['themeId']));
        $theme->status='active';
        $theme->save();
        $this->redirect('index');
    }
}
