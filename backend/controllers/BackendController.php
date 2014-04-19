<?php
namespace backend\controllers;

use yii\web\Controller;

/**
 * That is a default controller, for all controllers in backend application. 
 * @author klyukin 
 */
class BackendController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['backend'],
					],
				],
			],
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}
}
?>
