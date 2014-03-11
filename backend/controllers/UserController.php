<?php
namespace backend\controllers;

use common\models\User;

class UserController extends BackendController
{
	public function actionIndex()
	{   
            $params = [];
            $params['users'] = User::find()->all();
            return $this->render('index', $params);
	}
}
