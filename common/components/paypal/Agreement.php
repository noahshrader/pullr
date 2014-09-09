<?php
namespace common\components\paypal;

use PayPal\Common\PPModel;

/**
 * A resource representing an agreement
 * @package common\components\paypal
 */
class Agreement extends PPModel{

    /**
     * Get identifier of the agreement. Assigned in response
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set identifier of the agreement. Assigned in response
     * @param string $id
     * @return $this
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Get name of the agreement
     * @return mixed
     */
    public function getName(){
        return $this->id;
    }

    /**
     * Set name of the agreement
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Get description of the agreement
     * @return mixed
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Set description of the agreement
     * @param string $desc
     * @return $this
     */
    public function setDescription($desc){
        $this->description = $desc;
        return $this;
    }

    /**
     * Get start date of the agreement. Date format yyyy-MM-dd z, as defined in ISO8601
     * @return mixed
     */
    public function getStart_date(){
        return $this->start_date;
    }

    /**
     * Set start date of the agreement. Date format yyyy-MM-dd z, as defined in ISO8601
     * @param string $date
     * @return $this
     */
    public function setStart_date($date){
        $this->start_date = $date;
        return $this;
    }

    /**
     * Get details of the buyer who is enrolling in this agreement. This information is gathered from execution of the approval URL
     * @return mixed
     */
    public function getPayer(){
        return $this->payer;
    }

    /**
     * Set details of the buyer who is enrolling in this agreement. This information is gathered from execution of the approval URL
     * @param \Paypal\Api\Payer $payer
     * @return $this
     */
    public function setPayer($payer){
        $this->payer = $payer;
        return $this;
    }

    /**
     * Get shipping address object of the agreement, which should be provided if it is different from the default address
     * @return mixed
     */
    public function getShipping_address(){
        return $this->shipping_address;
    }

    /**
     * Set shipping address object of the agreement, which should be provided if it is different from the default address
     * @param \PayPal\Api\ShippingAddress $address
     * @return $this
     */
    public function setShipping_address($address){
        $this->shipping_address = $address;
        return $this;
    }

    /**
     * Get default merchant preferences from the billing plan are used, unless override preferences are provided here
     * @return mixed
     */
    public function  getOverride_merchant_preferences(){
        return $this->override_merchant_preferences;
    }

    /**
     * Set default merchant preferences from the billing plan are used, unless override preferences are provided here
     * @param MerchantPreferences $prefs
     * @return $this
     */
    public function setOverride_merchant_preferences($prefs){
        $this->override_merchant_preferences = $prefs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverride_charge_models(){
        return $this->override_charge_models;
    }

    /**
     * Set array of override_charge_model for this agreement if needed to change the default models from the billing plan
     * @param \common\components\paypal\OverrideChargeModel[] $models
     * @return $this
     */
    public function setOverride_charge_models($models){
        $this->override_charge_models = $models;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlan(){
        return $this->plan;
    }

    /**
     * @param \common\components\paypal\Plan $plan
     * @return $this
     */
    public function setPlan($plan){
        $this->plan = $plan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreate_time(){
        return $this->create_time;
    }

    /**
     * @param string $time
     * @return $this
     */
    public function setCreate_time($time){
        $this->create_time = $time;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdate_time(){
        return $this->update_time;
    }

    /**
     * @param string $time
     * @return $this
     */
    public function setUpdate_time($time){
        $this->update_time = $time;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinks(){
        return $this->links;
    }

    /**
     * @param \PayPal\Api\Links[] $links
     * @return $this
     */
    public function setLinks($links){
        $this->links = $links;
        return $this;
    }
}
