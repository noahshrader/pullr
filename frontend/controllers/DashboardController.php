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

        // dashboard statistics calculations
        $dashboard['overall'] = $this->calculateDashboardStats('overall');
        $dashboard['today'] = $this->calculateDashboardStats('today');
        $dashboard['month'] = $this->calculateDashboardStats('month');

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

    private function calculateDashboardStats($period = 'overall'){
        // total campaigns count
        $userCampaigns = Donation::find()
                            ->where(['campaignUserId' => \Yii::$app->user->id])
                            ->orWhere(['parentCampaignUserId' => \Yii::$app->user->id])
                            ->andWhere('paymentDate > 0');
        if($period == 'today'){
            $userCampaigns->andWhere('DATE(FROM_UNIXTIME(paymentDate)) = CURDATE()');
        }
        if($period == 'month'){
            $userCampaigns->andWhere('MONTH(DATE(FROM_UNIXTIME(paymentDate))) = MONTH(CURDATE())')
                          ->andWhere('YEAR(DATE(FROM_UNIXTIME(paymentDate))) = YEAR(CURDATE())');
        }
        $totalCampaigns = $userCampaigns->count('DISTINCT parentCampaignUserId');

        // donations calculations
        $todayDonations = Donation::find()
                          ->where(['campaignUserId' => \Yii::$app->user->id])
                          ->orWhere(['parentCampaignUserId' => \Yii::$app->user->id])
                          ->andWhere('paymentDate > 0');
        if($period == 'today'){
            $todayDonations->andWhere('DATE(FROM_UNIXTIME(paymentDate)) = CURDATE()');
        }
        if($period == 'month'){
            $todayDonations->andWhere('MONTH(FROM_UNIXTIME(paymentDate)) = MONTH(CURDATE())')
                           ->andWhere('YEAR(FROM_UNIXTIME(paymentDate)) = YEAR(CURDATE())');
        }
        $totalDonations = $todayDonations->count();
        $totalDonors = $todayDonations->count('DISTINCT email');
        $totalRaised = $todayDonations->sum('amount');
        $personalRaisedRec = Donation::find()
                             ->joinWith('campaign', true, 'INNER JOIN')
                             ->where(['campaignUserId' => \Yii::$app->user->id])
                             ->andWhere(['type' => 'Personal Fundraiser'])
                             ->andWhere('paymentDate > 0');
        if($period == 'today'){
            $personalRaisedRec->andWhere('DATE(FROM_UNIXTIME(paymentDate)) = CURDATE()');
        }
        if($period == 'month'){
            $personalRaisedRec->andWhere('MONTH(FROM_UNIXTIME(paymentDate)) = MONTH(CURDATE())')
                              ->andWhere('YEAR(FROM_UNIXTIME(paymentDate)) = YEAR(CURDATE())');
        }
        $personalRaised = $personalRaisedRec->sum('amount');
        $charityRaised = $totalRaised - $personalRaised;


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
