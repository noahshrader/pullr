<?php
namespace common\components\paypal;

use PayPal\Common\PPModel;
use PayPal\Api\Currency;

/**
 * Resource representing merchant preferences like max failed attempts,set up fee and others for a plan
 * @package common\components\paypal
 */
class MerchantPreferences extends PPModel{

    /**
     * Get notify URL on agreement creation. Assigned in response.
     * @return mixed
     */
    public function getNotifyUrl(){
        return $this->notify_url;
    }

    /**
     * @param string $url
     */
    public function setNotify_url($url){
        $this->notify_url = $url;
    }

    /**
     * Get payment types that are accepted for this plan. Assigned in response.
     * @return mixed
     */
    public function getAcceptedPaymentType(){
        return $this->accepted_payment_type;
    }

    /**
     * Set payment types that are accepted for this plan. Assigned in response.
     * @param string $type
     */
    public function setAccepted_payment_type($type){
        $this->accepted_payment_type = $type;
        return $this;
    }

    /**
     * Get char_set for this plan. Assigned in response.
     * @return mixed
     */
    public function getCharSet(){
        return $this->char_set;
    }

    public function setChar_set($charset){
        $this->char_set = $charset;
        return $this;
    }

    /**
     * Get identifier of the merchant_preferences. Assigned in response.
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Set setup fee amount. Default is 0
     * @param \PayPal\Api\Currency $fee
     * @return $this
     */
    public function setSetup_fee($fee){
        $this->setup_fee = $fee;
        return $this;
    }

    /**
     * Set redirect URL on cancellation of agreement request
     * @param string $url
     * @return $this
     */
    public function setCancel_url($url){
        $this->cancel_url = $url;
        return $this;
    }

    /**
     * Set redirect URL on creation of agreement request
     * @param string $url
     * @return $this
     */
    public function setReturn_url($url){
        $this->return_url = $url;
        return $this;
    }

    /**
     * Set total number of failed attempts allowed. Default is 0, representing an infinite number of failed attempts
     * @param string $attemptsCount
     * @return $this
     */
    public function setMax_fail_attempts($attemptsCount){
        $this->max_fail_attempts = $attemptsCount;
        return $this;
    }

    /**
     * Set allow auto billing for the outstanding amount of the agreement in the next cycle. Default is false.
     * @param string $billAmount
     * @return $this
     */
    public function setAuto_bill_amount($billAmount){
        $this->auto_bill_amount = $billAmount;
        return $this;
    }

    /**
     * Set action to take if a failure occurs during initial payment. Default is continue
     * @param string $action
     * @return $this
     */
    public function setInitial_fail_amount_action($action){
        $this->initial_fail_amount_action = $action;
        return $this;
    }
} 