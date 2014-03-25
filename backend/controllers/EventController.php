<?php
namespace backend\controllers;

use common\models\Event;

class EventController extends BackendController
{
	public function actionIndex()
	{   
            $status = isset($_POST['status']) ? $_POST['status'] : Event::STATUS_ACTIVE;
            $params = [];
            $params['events'] = Event::find()->where(['status' => $status])->all();
            $params['status'] = $status;
            return $this->render('index', $params);
	}
}
