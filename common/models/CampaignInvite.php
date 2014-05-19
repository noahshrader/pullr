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
     * @return Campaign
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
