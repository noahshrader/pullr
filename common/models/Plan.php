<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\components\PullrPayment;
use common\components\Application;
use common\models\mail\Mail;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Plan extends ActiveRecord {
    const PLAN_BASE = 'Basic';
    const PLAN_PRO = 'Pro';
    const SUBSCRIPTION_YEAR = 'year';
    const SUBSCRIPTION_MONTH = 'month';
    const SUBSCRIPTION_THREE_MONTH = 'three_month';
    
    public static $PLANS = [self::PLAN_BASE, self::PLAN_PRO];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_plan';
    }
    
    public function scenarios() {
        return [
            'default' => ['plan','expire']
        ];
    }
    
    /**
     * Prolong pro account 
     */
    public function prolong($money){
        $params = PullrPayment::getPaymentParamsForMoney($money);
        /** @var string */
        $oldPlan = $this->user->getPlan(); 
        if ($this->expire < time()){
            $this->expire = time();
        }
        
        $this->expire +=$params['days']*24*60*60;
        $this->plan = self::PLAN_PRO;
        $this->subscription = $params['subscription'];
        $this->save();
        
        $log = new PlanHistory();
        $log->userId = $this->id;
        $log->plan = self::PLAN_PRO;
        $log->date = time();
        $log->days = $params['days'];
        $log->save();
        
        if ($oldPlan == self::PLAN_BASE){
            $content = Application::render('@console/views/mail/proActivatedEmail', [
                    'subscription' => $params['subscription'],
                    'user' => $this->user
                ]);

            Mail::sendMail(\Yii::$app->params['adminEmails'], 'User activated pro-account', $content, 'proAccountActivation');
        }
    }
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
}
