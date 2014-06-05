<?php
namespace common\models;

use common\models\User;
use yii\db\ActiveRecord;
use Yii;

/**
 * Signup form
 */
class Donation extends ActiveRecord
{

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_donation';
    }
    
    public function scenarios() {
        return [
            'default' => ['name','email','comments','amount','createdDate']
        ];
    }
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}
}
 