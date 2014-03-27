<?php

namespace frontend\models\site;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * Login form
 */
class DeactivateAccount extends ActiveRecord {

    /**
     * how much time should be passed before account should be deleted in seconds
     * 30 days = 30*24*60*60 = 2592000
     */
    const DEACTIVATION_PERIOD = 2592000;
//    const DEACTIVATION_PERIOD = 20;

    public $password;

    public static function tableName() {
        return 'tbl_deactivateaccount';
    }

    public function scenarios() {
        return [
            'default' => ['reason', 'password'],
            'processing' => ['processing'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        if ($this->getScenario() == 'default') {
            return [
                ['reason', 'filter', 'filter' => 'trim'],
                ['reason', 'filter', 'filter' => 'strip_tags'],
                ['password', 'required'],
                ['password', 'validatePassword'],
            ];
        } else {
            return [];
        }
    }

    public function attributeLabels() {
        return [
            'reason' => "We are sorry you want to leave. Let us know why or what we could do better."
        ];
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->userId = \Yii::$app->user->id;
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

    /**
     * Validates the password.
     */
    public function validatePassword() {
        if (!$this->password) {
            $this->addError('password', 'Password is required');
            return;
        }
        $user = \Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Incorrect password.');
        }
    }

}
