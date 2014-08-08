<?php

namespace frontend\controllers;

use common\models\Donation;
use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;
use common\models\notifications\RecentActivityNotification;
use common\models\query\CampaignQuery;
use common\models\twitch\TwitchUser;
use common\models\Campaign;

class DashboardController extends FrontendController {
    public function actionIndex() {
        $userId = \Yii::$app->user->id;
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        $campaignInvites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        $recentActivity = RecentActivityNotification::find()->andWhere(['userId' => $userId])->orderBy('DATE DESC')->limit(10)->all();
        $twitchUser = TwitchUser::findOne($userId);

        // overall statistics calculations
        $dashboard['overall'] = $this->calculateDashboardStats(Campaign::find()->where(['userId' => $userId]));

        // today statistics calculations
        $dashboard['today'] = $this->calculateDashboardStats(Campaign::find()->where(['userId' => $userId])->andWhere('DATE(date) = CURDATE()'));

        // month statistics calculations
        $dashboard['month'] = $this->calculateDashboardStats(Campaign::find()->where(['userId' => $userId])->andWhere('MONTH(DATE(date)) = MONTH(CURDATE())'));

        return $this->render('index',[
            'systemNotification' => $systemNotification, 
            'campaignInvites' => $campaignInvites,
            'recentActivity' => $recentActivity,
            'twitchUser' => $twitchUser,
            'dashboard' => $dashboard,
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

    private function calculateDashboardStats(CampaignQuery $userCampaigns){
        $totalRaised = $userCampaigns->sum('amountRaised');
        $charityRaised = Donation::getDonationsForCampaigns($userCampaigns->all())->sum('amount');
        $personalRaised = $totalRaised - $charityRaised;

        $totalCampaigns = $userCampaigns->count();
        $totalDonations = $userCampaigns->sum('numberOfDonations');
        $totalDonors = $userCampaigns->sum('numberOfUniqueDonors');

        $totalRaised = $totalRaised ?: 0;
        $charityRaised = $charityRaised ?: 0;
        $totalDonations = $totalDonations ?: 0;
        $totalDonors = $totalDonors ?: 0;

        return array(
            'totalRaised' => $totalRaised,
            'personalRaised' => $personalRaised,
            'charityRaised' => $charityRaised,
            'totalCampaigns' => $totalCampaigns,
            'totalDonations' => $totalDonations,
            'totalDonors' => $totalDonors
        );
    }
}
