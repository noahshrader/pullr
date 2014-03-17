<?php
namespace backend\controllers;

use common\models\Event;

class EventController extends BackendController
{
	public function actionIndex()
	{   
            $params = [];
            $params['events'] = Event::find()->all();
            return $this->render('index', $params);
	}
}
