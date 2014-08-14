<?php

namespace frontend\controllers;

use common\models\Donation;
use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;
use common\models\notifications\RecentActivityNotification;
use common\models\query\CampaignQuery;
use common\models\twitch\TwitchUser;
use common\models\Campaign;
use common\models\User;

class DashboardController extends FrontendController {
    public function actionIndex() {
        $user = \Yii::$app->user->identity;
        /**@var User $user*/
        $userId = $user->getId();
        $systemNotification = SystemNotification::getNotificationForUser($userId);
        $campaignInvites = CampaignInvite::findAll(['userId' => $userId, 'status' => CampaignInvite::STATUS_PENDIND]);
        $recentActivity = RecentActivityNotification::find()->andWhere(['userId' => $userId])->orderBy('DATE DESC')->limit(10)->all();
        $twitchUser = TwitchUser::findOne($userId);

        // overall statistics calculations
        $dashboard['overall'] = $this->calculateDashboardStats($user->getCampaigns(Campaign::STATUS_ACTIVE)->orderBy('id DESC'));

        // today statistics calculations
        $parentCampaigns = $user->getParentCampaigns()->andWhere('DATE(date) = CURDATE()');
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->andWhere('DATE(date) = CURDATE()');
        $dashboard['today'] = $this->calculateDashboardStats($campaigns->union($parentCampaigns)->orderBy('id DESC'));

        // month statistics calculations
        $parentCampaigns = $user->getParentCampaigns()->andWhere('MONTH(DATE(date)) = MONTH(CURDATE())');
        $campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->andWhere('MONTH(DATE(date)) = MONTH(CURDATE())');
        $dashboard['month'] = $this->calculateDashboardStats($campaigns->union($parentCampaigns)->orderBy('id DESC'));

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
