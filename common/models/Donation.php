<?php
namespace common\models;

use common\models\User;
use yii\db\ActiveRecord;
use Yii;
use common\models\Campaign;

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
        
    /**
     * 
     * @return Campaign
     */
    public function getCampaign() {
        return $this->hasOne(Campaign::className(), ['id' => 'campaignId']);
    }
    
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
 