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
     * 20 days = 20*24*60*60 = 1728000
     */
    const DEACTIVATION_PERIOD = 1728000;

    public static function tableName() {
        return 'tbl_deactivateaccount';
    }

    public function scenarios() {
        return [
            'default' => ['reason'],
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
            ];
        } else {
            return [];
        }
    }

    public function attributeLabels() {
        return [
            'reason' => "Let us know why you are leaving"
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
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
