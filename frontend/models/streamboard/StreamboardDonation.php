<?php

namespace frontend\models\streamboard;

use common\models\Donation;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use common\models\User;
use yii\db\Query;

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

    /**
     * @param $donationId integer
     * @param $userId integer
     * @param $value boolean
     * @description It changes [nameHidden] field not only for current donationId, but it also changes [nameHidden]
     * for donations with same [campaignId] && [userId] && [email]
     */
    public static function setNameHidden($donationId, $userId, $value){
        $donation = Donation::findOne($donationId);
        $name = $donation->nameFromForm;
        $email = $donation->email;
        $user = User::findOne($userId);
        if ($email){
            //limiting query to contain just last 1000 donations for selected campaign to prevent performance issues
            $subQuery = $user->getDonations()->andWhere(['campaignId' => $donation->campaignId])->select(['id', 'nameFromForm', 'email'])->limit(1000);

            $ids = (new Query())->from(['donation' => $subQuery])->andWhere(['nameFromForm' => $name, 'email' => $email])->select('id')->column();
        } else {
            $ids = [$donationId];
        }

        $streamboards = StreamboardDonation::find()->where(['in', 'donationId', $ids])->andWhere(['userId' => $userId])->all();
        $streamboardsIds = [];
        foreach ($streamboards as $streamboard){
            $streamboardsIds[] = $streamboard->donationId;
        }

        $newIds = array_diff($ids, $streamboardsIds);
        foreach ($newIds as $id){
            $streamboard = new StreamboardDonation();
            $streamboard->donationId = $id;
            $streamboard->userId = $userId;
            $streamboards[] = $streamboard;
        }
        foreach ($streamboards as $streamboard){
            $streamboard->nameHidden = $value;
            $streamboard->save();
        }
    }

    public static function setForDonation($donationId, $userId, $property, $value){
        if (!in_array($property, ['nameHidden','wasRead'])){
            throw new ErrorException('Wrong property');
        }

        // we have more complicated logic for nameHidden
        if ($property == 'nameHidden'){
            self::setNameHidden($donationId, $userId, $value);
            return;
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
