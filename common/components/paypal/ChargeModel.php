<?php
namespace common\components\paypal;

use PayPal\Api\Currency;
use PayPal\Common\PPModel;

/**
 * A resource representing a charge model for a payment definition.
 * @package common\components\paypal
 */
class ChargeModel extends  PPModel{

    /**
     * Get identifier of the charge model. Assigned in response
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set identifier of the charge model. Assigned in response
     * @param string $id
     */
    public function setId($id){
        $this->id = $id;
    }

    /**
     * Get type of charge model, possible values can be shipping/tax
     * @return mixed
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Set type of charge model, possible values can be shipping/tax
     * @param string $type
     * @return $this
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }

    /**
     * Get specific amount for this charge model
     * @return mixed
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * Set specific amount for this charge model
     * @param \PayPal\Api\Currency $amount
     * @return $this
     */
    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }
} 