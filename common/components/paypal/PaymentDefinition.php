<?php
namespace common\components\paypal;

use PayPal\Common\PPModel;
use PayPal\Api\Currency;

/**
 * Resource representing payment definition scheduling information
 * @package common\components\paypal
 */
class PaymentDefinition extends PPModel{

    /**
     * @param string $id
     */
    public function setId($id){
        $this->id = $id;
    }

    /**
     * Get identifier of the payment_definition. Assigned in response.
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set name of the payment definition
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Set type of the payment definition. Possible types include: TRIAL and REGULAR
     * @param string $type
     * @return $this
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }

    /**
     * Set how frequently the customer should be charged.
     * @param string $frequencyInterval
     * @return $this
     */
    public function setFrequency_interval($frequencyInterval){
        $this->frequency_interval = $frequencyInterval;
        return $this;
    }

    /**
     * Set frequency of the payment definition offered
     * @param string $frequency
     * @return $this
     */
    public function setFrequency($frequency){
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * Set number of cycles in this payment definition
     * @param string $cycles
     * @return $this
     */
    public function setCycles($cycles){
        $this->cycles = $cycles;
        return $this;
    }

    /**
     * Set amount that will be charged at the end of each cycle for this payment definition
     * @param \PayPal\Api\Currency $amount
     * @return $this
     */
    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }

    /**
     * Set array of charge_models for this payment definition
     * @param ChargeModel[] $chargeModels
     * @return $this
     */
    public function setCharge_models(array $chargeModels){
        $this->charge_models = $chargeModels;
        return $this;
    }
} 