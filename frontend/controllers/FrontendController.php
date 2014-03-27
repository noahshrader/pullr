<?php
namespace frontend\controllers;

use yii\web\Controller;

/**
 * That is a default controller, for all controllers in frontend application. 
 * @author klyukin 
 */
class FrontendController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\web\AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['frontend'],
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
