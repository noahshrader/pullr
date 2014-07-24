<?php

namespace frontend\controllers;

use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;
use common\models\notifications\RecentActivityNotification;
use common\models\twitch\TwitchUser;


class DashboardController extends FrontendController {
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        $campaignInvites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        $recentActivity = RecentActivityNotification::find()->andWhere(['userId' => $userId])->orderBy('DATE DESC')->limit(10)->all();
        $twitchUser = TwitchUser::findOne($userId);
        return $this->render('index',[
            'systemNotification' => $systemNotification, 
            'campaignInvites' => $campaignInvites,
            'recentActivity' => $recentActivity,
            'twitchUser' => $twitchUser
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
            $invite->approve();
        }
        $this->redirect('app');
    }
}
