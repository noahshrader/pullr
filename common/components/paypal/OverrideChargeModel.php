<?php
namespace common\components\paypal;

use PayPal\Common\PPModel;

/**
 * A resource representing an override_charge_model to be used during creation of the agreement
 * @package common\components\paypal
 */
class OverrideChargeModel extends PPModel{

    /**
     * Get ID of charge model
     * @return mixed
     */
    public function getCharge_id(){
        return $this->charge_id;
    }

    /**
     * Set ID of charge model
     * @param string $id
     * @return $this
     */
    public function setCharge_id($id){
        $this->charge_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * Set updated Amount to be associated with this charge model
     * @param \PayPal\Api\Currency $amount
     * @return $this
     */
    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }

} 