<?php
namespace common\models;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use common\models\Campaign;
use yii\db\Query;

/**
 * @property integer $id
 * @property string $name - Returns concatenation of [[firstName]] and [[lastName]] in case one of them exists and space symbol between them. In either case it returns [[nameFromForm]]
 * @property string $nameFromForm
 * @property string $firstName
 * @property string $lastName
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
                if (!$this->createdDate){
                    $this->createdDate = time();
                }
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
    
    public static function findByEmailAndUserId($email, $userId){
        return Donation::find()->where(['or', 'campaignUserId = :userId', 'parentCampaignUserId = :userId'])->
                andWhere(['email' => $email])->andWhere('paymentDate > 0')->orderBy('paymentDate DESC')
                ->addParams(['userId' => $userId]);
    }

    /**
     * @param $campaigns Campaign[]
     * @return Query
     */
    public static function getDonationTableQueryForCampaigns($campaigns){
        $ids = [];
        foreach ($campaigns as $campaign){
            $ids[] = $campaign->id;
        }
        return (new Query())->select('id, SUM(AMOUNT) sum')->from(Donation::tableName())->
            where(['or', ['in', 'campaignId', $ids], ['in', 'parentCampaignId', $ids]])
            ->andWhere('paymentDate > 0');
    }

    /**
     * @param $campaigns Campaign[]
     * @return ActiveQuery
     */
    public static function getDonationsForCampaigns($campaigns){
        $ids = [];
        foreach ($campaigns as $campaign){
            $ids[] = $campaign->id;
        }
        return Donation::find()->where(['or', ['in', 'campaignId', $ids], ['in', 'parentCampaignId', $ids]])
            ->andWhere('paymentDate > 0');
    }

    /**
     * @param $campaigns Campaign[]
     * @param $limit integer
     * @param $nameFromForm - if true, only names from Form will be returned
     * @return string[]
     */
    public static function getTopDonorsForCampaigns($campaigns, $limit, $nameFromForm = false){
        $query = self::getDonationTableQueryForCampaigns($campaigns)->andWhere('email <> ""')
            ->groupBy('email')->orderBy('sum DESC')->limit($limit);
        $donationIds = $query->column();
        $donations = Donation::findAll($donationIds);
        usort($donations, function(Donation $d1,Donation $d2) use (&$donationIds){
            return (array_search($d1->id, $donationIds) > array_search($d2->id, $donationIds)) ? '1' : '-1';
        });
        /*we are using model function name, because logic maybe different to apply for getting names*/
        $names = [];
        foreach ($donations as $donation){
            $names[] = $nameFromForm ? $donation->nameFromForm : $donation->name;
        }
        return $names;
    }

    /**
     * @param $campaigns Campaign[]
     * @return Donation|null
     */
    public static function getTopDonation($campaigns){
        return self::getDonationsForCampaigns($campaigns)->orderBy('amount DESC')->one();
    }
}
 