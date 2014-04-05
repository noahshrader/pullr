<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\Event;

class EventController extends FrontendController {
    public function actionIndex(){
        $user = \Yii::$app->user->identity;
        $eventsQuery = Event::find()->active()->andWhere(['userId' => $user->id]);
        
        $params = [];
        $params['amountRaised'] = $eventsQuery->sum('amountRaised');
        $params['goalAmount'] = $eventsQuery->sum('goalAmount');
        $params['numberOfDonations'] = $eventsQuery->sum('numberOfDonations');
        $params['numberOfUniqueDonors'] = $eventsQuery->sum('numberOfUniqueDonors');
        
        $params['events'] = $eventsQuery->orderBy('amountRaised DESC')->all();
        
        if (sizeof($params['events'])==0){
            $params['amountRaised'] = $params['goalAmount'] = $params['numberOfDonations'] = $params['numberOfUniqueDonors'] = 0;
        }
        return $this->render('index', $params);
    }
}
