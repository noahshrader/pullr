<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Payment;
use common\components\PullrPayment;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Plan extends ActiveRecord {
    const PLAN_BASE = 'base';
    const PLAN_PRO = 'pro';
    const SUBSCRIPTION_YEAR = 'year';
    const SUBSCRIPTION_MONTH = 'month';
    
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
                
        if ($this->expire < time()){
            $this->expire = time();
        }
        
        $this->expire +=$params['days']*24*60*60;
        $this->plan = self::PLAN_PRO;
        $this->subscription = $params['subscription'];
        $this->save();
    }
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
}
