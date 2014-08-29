<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
/**
 * That is a default controller, for all controllers in frontend application. 
 */
class FrontendController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
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
