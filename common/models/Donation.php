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
                ['name', 'filter', 'filter' => 'strip_tags'],
                ['email', 'filter', 'filter' => 'strip_tags'],
                ['comments', 'filter', 'filter' => 'strip_tags'],
            ];
	}
}
 