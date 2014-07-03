<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\notifications\SystemNotification;

class DashboardController extends FrontendController {
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        return $this->render('index',[
            'systemNotification' => $systemNotification
        ]);
    }
    
    public function actionClosesystemmessage($id){
        $userId = \Yii::$app->user->id;
        SystemNotification::readNotificationForUser($userId, $id);
    }
}
