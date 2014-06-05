<?php

namespace frontend\controllers;

use common\models\User;
use common\models\Campaign;

/*
controller to view exported layout
 *  */
class LayoutviewController extends FrontendController {

    public function actionView($userAlias, $layoutAlias) {
        if (ctype_digit($userAlias)){
            $user = User::findOne($userAlias);
        } else {
            $user = User::findOne(['uniqueName' => $userAlias]);
        }
        
        if (!$user){
            throw new \yii\web\NotFoundHttpException("Such user don't exist");
        }
        
        $campaign = Campaign::findOne(['userId' => $user->id, 'status' => Campaign::STATUS_ACTIVE, 'alias' => $layoutAlias]);
        if (!$campaign){
            throw new \yii\web\NotFoundHttpException("Such campaign don't exist for user");
        }
        
        echo $this->renderPartial('index',['campaign' => $campaign]);
        die;
    }
}
