<?php

namespace frontend\models\streamboard;

use yii\base\ErrorException;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * Class StreamboardCampaigns Is used to store options for donation for user (like donation was announced-read).
 * @package frontend\models\streamboard
 * @property integer $donationId
 * @property integer $userId
 * @property boolean $nameHidden
 * @property boolean $wasRead
 */
class StreamboardDonation extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_donation';
    }

    public static function setForDonation($donationId, $userId, $property, $value){
        if (!in_array($property, ['nameHidden','wasRead'])){
            throw new ErrorException('Wrong property');
        }

        $streamboard = StreamboardDonation::findOne(['donationId' => $donationId, 'userId' => $userId]);
        if (!$streamboard){
            $streamboard = new StreamboardDonation();
            $streamboard->donationId = $donationId;
            $streamboard->userId = $userId;
        }
        $streamboard->$property = $value;
        $streamboard->save();
    }
}
