<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Payment;

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
        $days = null;
        switch ($money){
            case \Yii::$app->params['yearSubscription']:
                $days = 365.25;
                $subscription = self::SUBSCRIPTION_YEAR;
                $amount = \Yii::$app->params['yearSubscription'];
                $paymentType = Payment::TYPE_PRO_MONTH;
                break;
            case \Yii::$app->params['monthSubscription']:
                $days = (365.25) / 12;
                $subscription = self::SUBSCRIPTION_MONTH;
                $amount = \Yii::$app->params['monthSubscription'];
                $paymentType = Payment::TYPE_PRO_YEAR;
                break;
            default:
                break;
        }
        
        if (!$days){
            return;
        }
        
        $payment = new Payment();
        $payment->userId = $this->id;
        $payment->amount = $amount;
        $payment->status = Payment::STATUS_APPROVED;
        $payment->type = $paymentType;
        $payment->save();
                
        if ($this->expire < time()){
            $this->expire = time();
        }
        
        $this->expire +=$days*24*60*60;
        $this->plan = self::PLAN_PRO;
        $this->subscription = $subscription;
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
