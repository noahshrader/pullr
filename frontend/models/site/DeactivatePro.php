<?php
namespace frontend\models\site;

use yii\db\ActiveRecord;
use common\models\User;

class DeactivatePro extends ActiveRecord{

    public $reasonId;

    private $reasons  = [
        0 => "Don't have time",
        1 => "Can't afford",
        2 => "Too expensive",
        3 => "Features are too basic",
        4 => "There are features i want but aren't there",
        5 => "Not enough features",
        6 => "Did all i wanted too"
    ];

    public static function tableName(){
        return "tbl_deactivatepro";
    }

    public function attributeLabels() {
        return [
            'reasonId' => "We're sorry to see you go. Why are you cancelling today?"
        ];
    }

    public function rules(){
        return [
            ['reasonId', 'required'],
        ];
    }

    public function getReasons(){
        return $this->reasons;
    }

    public function getReason(){
        return $this->reasons[$this->reasonId];
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->userId = \Yii::$app->user->id;
            $this->reason = $this->getReason();
            $this->creationDate = time();
        }
        return parent::beforeSave($insert);
    }

    /**
     *
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
} 