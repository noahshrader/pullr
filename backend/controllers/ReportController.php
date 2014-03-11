<?php
namespace backend\controllers;

use common\models\User;

class ReportController extends BackendController
{
	public function actionIndex()
	{   
            $params = [];
            $params['totalUsers'] = User::find()->where(['status' => User::STATUS_ACTIVE])->count();
            return $this->render('index', $params);
	}
}
