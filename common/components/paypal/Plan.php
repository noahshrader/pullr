<?php
namespace common\components\paypal;

use PayPal\Common\PPModel;

/**
 * Billing plan resource that will be used to create a billing agreement
 * @package common\components\paypal
 */
class Plan extends PPModel{

    /**
     * Get identifier of the billing plan. Assigned in response
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set identifier of the billing plan. Assigned in response
     * @param string $id
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Get status of the billing plan. Assigned in response
     * @return mixed
     */
    public function getState(){
        return $this->state;
    }

    /**
     * Set status of the billing plan. Assigned in response
     * @param string $state
     * @return $this
     */
    public function setState($state){
        $this->state = $state;
        return $this;
    }

    /**
     * Get details of the merchant who is creating this billing plan. Assigned in response
     * @return mixed
     */
    public function getPayee(){
        return $this->payee;
    }

    /**
     * Set details of the merchant who is creating this billing plan. Assigned in response
     * @param \PayPal\Api\Payee $payee
     * @return $this
     */
    public function setPayee($payee){
        $this->payee = $payee;
        return $this;
    }

    /**
     * Get time when the billing plan was created, represented as YYYY-MM-DDTimeTimezone format. Assigned in response
     * @return mixed
     */
    public function getCreate_time(){
        return $this->create_time;
    }

    /**
     * Set time when the billing plan was created, represented as YYYY-MM-DDTimeTimezone format. Assigned in response
     * @param string $time
     * @return $this
     */
    public function setCreate_time($time){
        $this->create_time = $time;
        return $this;
    }

    /**
     * Get time when this billing plan was updated, represented as YYYY-MM-DDTimeTimezone format. Assigned in response
     * @return mixed
     */
    public function getUpdate_time(){
        return $this->update_time;
    }

    /**
     * Set time when this billing plan was updated, represented as YYYY-MM-DDTimeTimezone format. Assigned in response
     * @param string $time
     * @return $this
     */
    public function setUpdate_time($time){
        $this->update_time = $time;
        return $this;
    }

    /**
     * Get array of terms for this billing plan. Assigned in response
     * @return mixed
     */
    public function getTerms(){
        return $this->terms;
    }

    /**
     * Set array of terms for this billing plan. Assigned in response
     * @param string $terms
     * @return $this
     */
    public function setTerms($terms){
        $this->terms = $terms;
        return $this;
    }

    /**
     * Get name of the billing plan
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Set name of the billing plan
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Get description of the billing plan
     * @return mixed
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Set description of the billing plan
     * @param string $description
     * @return $this
     */
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    /**
     * Get type of the billing plan
     * @return mixed
     */
    public function getType(){
        return $this->type;
    }

    /**
     * Set type of the billing plan
     * @param string $type
     * @return $this
     */
    public function setType($type){
        $this->type = $type;
        return $this;
    }

    /**
     * Set an array of payment definitions for this billing plan
     * @param PaymentDefinition[] $paymentDefinitions
     * @return $this
     */
    public function setPayment_definitions(array $paymentDefinitions){
        $this->payment_definitions = $paymentDefinitions;
        return $this;
    }

    /**
     * Set specific preferences such as: set up fee, max fail attempts, autobill amount, and others that are configured for this billing plan
     * @param MerchantPreferences $merchantPreferences
     * @return $this
     */
    public function setMerchant_preferences($merchantPreferences){
        $this->merchant_preferences = $merchantPreferences;
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