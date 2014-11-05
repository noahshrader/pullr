<?php
namespace frontend\models\site;

use yii\base\Model;

class ManualDonation extends Model{

    public $name;
    public $email;
    public $amount;
    public $dateCreated;
    public $campaignId;
    public $comments;

    public function attributeLabels() {
        return [
            //'reasonId' => "We're sorry to see you go. Why are you cancelling today?"
        ];
    }

    public function rules(){
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['email', 'email', 'message' => 'Invalid Email address'],
            ['email', 'filter', 'filter' => 'strip_tags'],
            ['amount', 'required'],
            ['amount', 'double'],
            ['dateCreated', 'required'],
            ['comments', 'filter', 'filter' => 'strip_tags'],
            ['campaignId', 'required']
        ];
    }
} 