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
            'default' => ['nameFromForm','email','comments','amount','createdDate']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['nameFromForm', 'filter', 'filter' => 'strip_tags'],
            ['email', 'filter', 'filter' => 'strip_tags'],
            ['comments', 'filter', 'filter' => 'strip_tags'],
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            if ($insert){
                $campaign = Campaign::findOne($this->campaignId);
                $this->campaignUserId = $campaign->userId;
                $parentCampaign = $campaign->isChild() ? Campaign::findOne($campaign->parentCampaignId) : $campaign;
                $this->parentCampaignId = $parentCampaign->id;
                $this->parentCampaignUserId = $parentCampaign->userId;
            }
            return true;
        } else {
            return false;
        }
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
    
    public function getName(){
        if ($this->firstName || $this->lastName){
            return $this->lastName.' '.$this->firstName;
        } else {
            return $this->nameFromForm;
        }
    }
}
 