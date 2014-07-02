<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\Campaign;
use common\models\User;

class CampaignInvite extends ActiveRecord {
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDIND = 'pending';
    const STATUS_DELETED = 'deleted';
    
    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_PENDIND, self::STATUS_DELETED];
    
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_campaignInvite';
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
    
    /**
     * 
     * @param type $userId
     * @param type $campaignId
     * @return boolean true if invite was added, false in case if user already have active invitation to that campaign
     */
    public static function addInvite($userId, $campaignId){
        $invite = CampaignInvite::findOne(['userId' => $userId, 'campaignId' => $campaignId]);
        if (!$invite) {
            $invite = new CampaignInvite();
            $invite->userId = $userId;
            $invite->campaignId = $campaignId;
            $invite->status = CampaignInvite::STATUS_PENDIND;
            $invite->lastChangeDate = time();
            $invite->save();
            return true;
        } else if (!in_array($invite->status, [CampaignInvite::STATUS_PENDIND, CampaignInvite::STATUS_ACTIVE])) {
            $invite->status = CampaignInvite::STATUS_PENDIND;
            $invite->lastChangeDate = time();
            $invite->save();
            return true;
        }
        
        return false;
    }
}
