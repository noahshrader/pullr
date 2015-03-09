<?php
namespace common\models;

use common\components\csvimport\CsvReader;
use common\components\csvimport\CsvReaderFactory;
use common\models\User;
use common\components\PullrUtils;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use frontend\models\streamboard\StreamboardDonation;
use yii\db\Query;

/**
 * @class Donation - only Donations with paymentDate > 0 represent real money donations, others just drafts which wait approvement of money transfering
 * @property integer $id
 * @property integer $userId
 * @property integer $campaignId
 * @property float $amount
 * @property string $name - Returns concatenation of [[firstName]] and [[lastName]] in case one of them exists and space symbol between them. In either case it returns [[nameFromForm]]
 * @property string $nameFromForm
 * @property string $comments
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property Campaign $campaign
 * @property StreamboardDonation $streamboard
 * @property integer $paymentDate if > 0 donation really was made.
 */
class Donation extends ActiveRecord
{
    public $sum = 0;
    const ANONYMOUS_NAME = 'Anonymous';
    const DEFAULT_EMAIL = 'no-email@mail.com';
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_donation';
    }

    public function scenarios() {
        return [
            'default' => ['nameFromForm','email','comments','amount','createdDate','sum'],
            'firstGiving' => ['nameFromForm','email','comments','amount','createdDate'],
            Campaign::TYPE_PERSONAL_FUNDRAISER => ['nameFromForm','comments','amount','createdDate']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'email', 'message' => 'Invalid Email address'],
            ['email', 'required', 'message' => 'Email is required', 'on' => 'firstGiving'],
            ['nameFromForm', 'filter', 'filter' => 'strip_tags'],
            ['nameFromForm', 'default', 'value' => self::ANONYMOUS_NAME],
            ['email', 'filter', 'filter' => 'strip_tags'],
            ['email', 'default', 'value' => self::DEFAULT_EMAIL],
            ['comments', 'filter', 'filter' => 'strip_tags']
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
     * @return ActiveQuery
     */
    public function getCampaign() {
        return $this->hasOne(Campaign::className(), ['id' => 'campaignId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStreamboard(){
        $userId = \Yii::$app->user->id;
        return $this->hasOne(StreamboardDonation::className(), ['donationId' => 'id'])->andWhere(['userId' => $userId]);
    }
    /**
     *
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @param bool $realNames - real names will be used when possible
     * @return string
     */
    public function getName($realNames = true){
        if ($this->firstName || $this->lastName){
            return $this->firstName.' '.$this->lastName;
        } else {
            return $this->nameFromForm ? $this->nameFromForm : self::ANONYMOUS_NAME;
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
    public static function getDonationTableQueryForCampaigns($campaigns, $sinceDate = null){
        $ids = [];
        foreach ($campaigns as $campaign){
            $ids[] = $campaign->id;
        }
        $query = (new Query())->select('id, SUM(AMOUNT) sum')->from(Donation::tableName())->
            where(['or', ['in', 'campaignId', $ids], ['in', 'parentCampaignId', $ids]]);
        if ($sinceDate){
           $query->andWhere('paymentDate > '.intval($sinceDate));
        } else {
           $query->andWhere('paymentDate > 0');
        }
        return $query;
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
     * @param $sinceDate - if is set, only donations with paymentDate > $sinceDate will be taken in query
     * @return string[]
     */
    public static function getTopDonorsForCampaigns($campaigns, $limit, $nameFromForm = false, $sinceDate = null, $goupBy = 'email'){
        $query = self::getDonationTableQueryForCampaigns($campaigns, $sinceDate)->andWhere('email <> ""');
        if($goupBy == 'email'){
            $query = $query->groupBy('email');
        }else if($goupBy == 'name'){
            $query = $query->groupBy('nameFromForm');
        }
        $query = $query->orderBy('sum DESC')->select('id , SUM(AMOUNT) sum, email, nameFromForm name');
        if ($limit){
            $query->limit($limit);
        }
        $rows = $query->all();
        $sumAry = [];
        $donationIds = [];
        foreach ($rows as $row) {
            $donationIds[] = $row['id'];
            if($goupBy == 'email'){
                $sumAry[$row['email']] = $row['sum'];
            }else if($goupBy == 'name'){
                $sumAry[$row['name']] = $row['sum'];
            }
        }

        $donations = Donation::find()->where(['id' => $donationIds])->orderBy('id desc')->all();

        /*we are using model function name, because logic maybe different to apply for getting names*/
        $donors = [];
        foreach ($donations as $donation){
            $name = $nameFromForm ? $donation->displayNameForDonation() : $donation->name;
            $key = $goupBy == 'email'?$donation['email']:$donation['nameFromForm'];
            $donors[] = [
                            'name' => $name,
                            'amount' => $sumAry[$key]*1,
                            'email' => $donation->email,
                            'campaignId' => $donation->campaignId,
                            'time' => $donation->paymentDate
                        ];
        }
        return $donors;
    }

    public static function getTopDonorsForCampaignsGroupByAmount($campaigns, $limit, $nameFromForm = false, $sinceDate = null)
    {
        $donors = static::getTopDonorsForCampaigns($campaigns, $limit, $nameFromForm, $sinceDate);
        $groupResult = [];
        foreach ($donors as $index => $donor) {
            $amount = $donor['amount'];
            if ( ! isset($groupResult[$amount])) {
                $groupResult[$amount] = [];

                foreach ($donors as $index2 => $donor2) {
                    if ($index2 >= $index && $donor2['amount'] == $amount) {
                        $groupResult[$amount][] = $donor2;
                    }
                }
            }
        }

        return $groupResult;
    }

    /**
     * get last donor for user campaigns
     * @param array $campaigns
     * @return array
     */
    public static function getLastDonorForCampaigns($campaigns) {
        $donor = self::getDonationsForCampaigns($campaigns)
                    ->orderBy('paymentDate DESC, id DESC')
                    ->select('id, amount, nameFromForm')
                    ->one();
        if ($donor) {
            return [
                'id' => $donor->id,
                'name' => $donor->displayNameForDonation(),
                'amount' => $donor->amount
            ];
        }
        return null;

    }


    /**
     * @param $campaigns Campaign[]
     * @return Donation|null
     */
    public static function getTopDonation($campaigns){
        return self::getDonationsForCampaigns($campaigns)->orderBy('amount DESC')->one();
    }

    /**
     * @param $donation Donation
     * @return string NameFromForm or Anonymous depends if user hid donor's name or donor's name is empty
     */
    public function displayNameForDonation(){
        $nameHidden = false;
        if ($this->streamboard){
            $nameHidden = $this->streamboard->nameHidden;
        }
        return $nameHidden || !$this->nameFromForm  ? Donation::ANONYMOUS_NAME : $this->nameFromForm;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->paymentDate > 0;
    }

    public static function importFromCsv($campaignId, $filename)
    {
        $donationsCsv = (new CsvReader)->GetRows($filename);

        foreach($donationsCsv as $csvRow)
        {
            $donation = new Donation();
            $donation->userId = \Yii::$app->user->id;
            $donation->createdDate = $donation->paymentDate = $csvRow->getTimestamp();
            $donation->campaignId = intval($campaignId);
            $donation->amount = $csvRow->getAmount();
            $donation->nameFromForm = $csvRow->getName();
            $donation->email = $csvRow->getEmail();
            $donation->comments = $csvRow->getComment();
            $donation->isManual = true;
            $donation->save();
        }
    }

}
