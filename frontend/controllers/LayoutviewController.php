<?php

namespace frontend\controllers;

use common\models\User;
use common\models\Layout;

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
        
        $layout = Layout::findOne(['userId' => $user->id, 'status' => Layout::STATUS_ACTIVE, 'alias' => $layoutAlias]);
        if (!$layout){
            throw new \yii\web\NotFoundHttpException("Such layout don't exist for user");
        }
        
        echo $this->renderPartial('index',['layout' => $layout]);
        die;
    }
}