<?php
namespace backend\controllers;

use common\models\Donation;
use common\models\User;
use common\models\Plan;
use common\models\PlanHistory;
use common\models\Payment;
use common\models\Charity;
use common\models\Campaign;
class ReportController extends BackendController
{   
        private function topCharities(){
            $result = [];
            $command = (new \yii\db\Query())->from(Campaign::tableName())
                    ->select(['charityId', 'COUNT(*) campaignsNumber ', 'SUM(amountRaised) amountRaised'])->where('charityId >= 0')
                    ->groupBy('charityId')->createCommand();
            
            $sql = '('.$command->getSql().') campaignAggregated';
            $maxes = (new \yii\db\Query)->from($sql)
                    ->select(['Max(campaignsNumber) maxCampaignsNumber', 'Max(amountRaised) maxAmountRaised'])
                    ->one();
            
            $topSelectedCharityId =(new \yii\db\Query())->from($sql)
                    ->where(['campaignsNumber' => $maxes['maxCampaignsNumber']])
                    ->select('charityId')
                    ->scalar();
            
            $result['topSelectedCharity'] = Charity::findOne($topSelectedCharityId);
            
            $topProfitCharityId =(new \yii\db\Query())->from($sql)
                    ->where(['amountRaised' => $maxes['maxAmountRaised']])
                    ->select('charityId')
                    ->scalar();
            
            $result['topProfitCharity'] = Charity::findOne($topProfitCharityId);
            return $result;
        }
        
        private function topUsers(){
            $result = [];
            $command = (new \yii\db\Query())->from(Campaign::tableName())
                    ->select(['userId', 'COUNT(*) campaignsNumber ', 'SUM(amountRaised) amountRaised'])
                    ->groupBy('userId')->createCommand();
            
            $sql = '('.$command->getSql().') campaignAggregated';
            $maxes = (new \yii\db\Query)->from($sql)
                    ->select(['Max(campaignsNumber) maxCampaignsNumber', 'Max(amountRaised) maxAmountRaised'])
                    ->one();
            
            $topSelectedCharityId =(new \yii\db\Query())->from($sql)
                    ->where(['campaignsNumber' => $maxes['maxCampaignsNumber']])
                    ->select('userId')
                    ->scalar();
            
            $result['userWithMostCampaigns'] = User::findOne($topSelectedCharityId);
            
            $topProfitCharityId =(new \yii\db\Query())->from($sql)
                    ->where(['amountRaised' => $maxes['maxAmountRaised']])
                    ->select('userId')
                    ->scalar();
            
            $result['topProfitUser'] = User::findOne($topProfitCharityId);
            return $result;
        }
        
        public function averageLengthOfPro(){
             $command = (new \yii\db\Query())->from(PlanHistory::tableName())
                    ->select(['userId', 'SUM(days) days'])
                    ->groupBy('userId')->createCommand();
             $sql = '('.$command->getSql().') userDays';
             $averageDays = (new \yii\db\Query)->from($sql)
                    ->select('AVG(days)')->scalar();
             return $averageDays;
        }
        
	public function actionIndex()
	{   
            $params = [];
            $params['totalUsers'] = User::find()->where(['status' => User::STATUS_ACTIVE])->count();
            $params['basicPlanUsers'] = Plan::find()->where(['or', 'plan = "'.Plan::PLAN_BASE.'"', 'expire < '.time()])->count();
            $params['proPlanUsers'] = Plan::find()->where(['and', 'plan = "'.Plan::PLAN_PRO.'"', 'expire > '.time()])->count();
            $params['totalRevenue'] = Payment::find()->where( ['status' => Payment::STATUS_APPROVED])->
                    andWhere(['or', 'type = "' .Payment::TYPE_PRO_MONTH.'"', 'type = "' . Payment::TYPE_PRO_YEAR . '"'])->sum('amount');
            $params['proPlanUsersMonthlyBased'] = Plan::find()->where(['and', 'plan = "'.Plan::PLAN_PRO.'"', 'expire > '.time(), 'subscription = "'. Plan::SUBSCRIPTION_MONTH.'"' ])->count();
            $params['proPlanUsersYearlyBased'] = Plan::find()->where(['and', 'plan = "'.Plan::PLAN_PRO.'"', 'expire > '.time(), 'subscription = "'. Plan::SUBSCRIPTION_YEAR.'"' ])->count();
            $params['averageMonths'] = round($this->averageLengthOfPro() / (365.25/12));
            $totalUsersTryPro = max(PlanHistory::find()->select('userId')->distinct()->count(),1);
            $params['retentionRate'] = round(100 * $params['proPlanUsers'] / $totalUsersTryPro);
            
            $params['totalAmountRaised'] = Campaign::find()->sum('amountRaised');
            $params['amountCurrentlyBeingRaised'] = Campaign::find()->active()->sum('amountRaised');
                $params['amountRaisedThisMonth'] = Donation::find()->where('MONTH(DATE(FROM_UNIXTIME(paymentDate))) = MONTH(CURDATE())')
                                                               ->andWhere('YEAR(DATE(FROM_UNIXTIME(paymentDate))) = YEAR(CURDATE())')
                                                               ->andWhere('paymentDate > 0')
                                                               ->sum('amount');
            $params['totalCampaigns'] = Campaign::find()->count();
            $params['currentCampaigns'] = Campaign::find()->active()->count();
            $params['campaignsThisMonth'] = Campaign::find()->where(['status' => Campaign::STATUS_ACTIVE])->andWhere('startDate >= '.strtotime(date('01-m-Y')))->count();
            
            $result = $this->topCharities();
            $params['topSelectedCharity'] = $result['topSelectedCharity'];
            $params['topProfitCharity'] = $result['topProfitCharity'];
            
            $result = $this->topUsers();
            $params['userWithMostCampaigns'] = $result['userWithMostCampaigns'];
            $params['topProfitUser'] = $result['topProfitUser'];
            
            
//            $charities =  Campaign::find()->select(['charityId', 'COUNT(*)'])->groupBy('charityId')->all();
            
            $topSelectedCharity = -1;
            $topSelectedCharityMax = 0;
//            foreach 
            return $this->render('index', $params);
	}
}
