<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;

class DashboardController extends FrontendController {
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        $campaignInvites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        return $this->render('index',[
            'systemNotification' => $systemNotification, 
            'campaignInvites' => $campaignInvites
        ]);
    }
    
    public function actionClosesystemmessage($id){
        $userId = \Yii::$app->user->id;
        SystemNotification::readNotificationForUser($userId, $id);
    }
    
    public function actionInvitedelete($id){
        $userId = \Yii::$app->user->id;
        $invite = CampaignInvite::findOne(['id' => $id, 'status' => CampaignInvite::STATUS_PENDIND, 
            'userId' => $userId]);
        if ($invite){
            $invite->status = CampaignInvite::STATUS_DELETED;
            $invite->save();
        }
        $this->redirect('app');
    }
    
    public function actionInviteapprove($id){
        $userId = \Yii::$app->user->id;
        $invite = CampaignInvite::findOne(['id' => $id, 'status' => CampaignInvite::STATUS_PENDIND, 
            'userId' => $userId]);
        if ($invite){
            $invite->status = CampaignInvite::STATUS_ACTIVE;
            $invite->save();
        }
        $this->redirect('app');
    }
}
