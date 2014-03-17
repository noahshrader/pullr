<?php
namespace backend\controllers;

use common\models\User;
use common\models\Plan;
use common\models\Payment;

class ReportController extends BackendController
{
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
            
            return $this->render('index', $params);
	}
}
