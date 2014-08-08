<?php

namespace frontend\controllers;

use common\models\Donation;
use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;
use common\models\notifications\RecentActivityNotification;
use common\models\twitch\TwitchUser;
use common\models\Campaign;


class DashboardController extends FrontendController {
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        $campaignInvites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        $recentActivity = RecentActivityNotification::find()->andWhere(['userId' => $userId])->orderBy('DATE DESC')->limit(10)->all();
        $twitchUser = TwitchUser::findOne($userId);

        $overallUserCampaigns = Campaign::find()->where(['userId' => $userId]);

        $overallTotalRaised = $overallUserCampaigns->sum('amountRaised');
        $overallCharityRaised = Donation::getDonationsForCampaigns($overallUserCampaigns->all())->sum('amount');
        $overallPersonalRaised = $overallTotalRaised - $overallCharityRaised;

        $overallTotalCampaigns = $overallUserCampaigns->count();
        $overallTotalDonations = $overallUserCampaigns->sum('numberOfDonations');
        $overallTotalDonors = $overallUserCampaigns->sum('numberOfUniqueDonors');

        $overallTotalRaised = $overallTotalRaised ?: 0;
        $overallCharityRaised = $overallCharityRaised ?: 0;
        $overallTotalDonations = $overallTotalDonations ?: 0;
        $overallTotalDonors = $overallTotalDonors ?: 0;

        return $this->render('index',[
            'systemNotification' => $systemNotification, 
            'campaignInvites' => $campaignInvites,
            'recentActivity' => $recentActivity,
            'twitchUser' => $twitchUser,
            'overallTotalRaised' => $overallTotalRaised,
            'overallPersonalRaised' => $overallPersonalRaised,
            'overallCharityRaised' =>  $overallCharityRaised,
            'overallTotalCampaigns' => $overallTotalCampaigns,
            'overallTotalDonations' => $overallTotalDonations,
            'overallTotalDonors' => $overallTotalDonors
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
