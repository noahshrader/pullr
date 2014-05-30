<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\Campaign;
use yii\web\NotFoundHttpException;
use common\components\Application;
use common\models\LayoutTeam;
use yii\web\Response;
use Yii;
use kartik\widgets\ActiveForm;
use common\components\UploadImage;
use common\models\Theme;
use common\models\Plan;
use common\models\Charity;
use common\models\User;
use common\models\CampaignInvite;

class CampaigninviteController extends FrontendController {
    public function actionIndex(){
        $userId = \Yii::$app->user->id;
        $invites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        if (sizeof($invites) == 0){
            $this->goHome();
        } else{
            return $this->render('index',['invites' => $invites]);
        }
    }
    
    public function actionDelete($id){
        $userId = \Yii::$app->user->id;
        $invite = CampaignInvite::findOne(['id' => $id, 'status' => CampaignInvite::STATUS_PENDIND, 
            'userId' => $userId]);
        if ($invite){
            $invite->status = CampaignInvite::STATUS_DELETED;
            $invite->save();
        }
        $this->redirect('campaigninvite');
    }
    
    public function actionApprove($id){
        $userId = \Yii::$app->user->id;
        $invite = CampaignInvite::findOne(['id' => $id, 'status' => CampaignInvite::STATUS_PENDIND, 
            'userId' => $userId]);
        if ($invite){
            $invite->status = CampaignInvite::STATUS_ACTIVE;
            $invite->save();
        }
        $this->redirect('campaigninvite');
    }
    
}