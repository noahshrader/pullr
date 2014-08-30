<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\HtmlPurifier;
use common\models\base\BaseImage;
use frontend\models\streamboard\StreamboardCampaign;

/**
 * @property integer $id
 * @property integer $parentCampaignId - equal to [[id]] if that campaign hasn't got parent campaign.
 * @property string $name
 * @property float $amountRaised
 * @property float $goalAmount
 * @property integer $numberOfDonations
 * @property integer $numberOfUniqueDonors
 * @property Campaign $parentCampaign
 * @property StreamboardCampaign $streamboard
 * @property string $donationDestination
 * @description To consider account on other the base you also should check expire field to be more than current time
 */
class Campaign extends ActiveRecord {

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_DELETED];

    const STREAM_SERVICE_TWITCH = 'Twitch';
    const STREAM_SERVICE_HITBOX = 'Hitbox';

    public static $STREAM_SERVICES = [self::STREAM_SERVICE_TWITCH, self::STREAM_SERVICE_HITBOX];
    
    const TYPE_PERSONAL_FUNDRAISER = 'Personal Fundraiser';
    const TYPE_CHARITY_FUNDRAISER = 'Charity Fundraiser';
    public static $TYPES = [self::TYPE_PERSONAL_FUNDRAISER, self::TYPE_CHARITY_FUNDRAISER];
    
    const LAYOUT_TYPE_SINGLE = 'Single Stream';
    const LAYOUT_TYPE_TEAM = 'Team Stream';
    const LAYOUT_TYPE_MULTI = 'Multi Stream';

    public static $LAYOUT_TYPES = [self::LAYOUT_TYPE_SINGLE, self::LAYOUT_TYPE_TEAM, self::LAYOUT_TYPE_MULTI];

//    const DONATION_PERSONAL_PAYPAL = 'Personal Paypal';
    const DONATION_PREAPPROVED_CHARITIES = 'Pre-approved Charities';
    const DONATION_CUSTOM_FUNDRAISER = 'Custom Fundraiser';
    
    public static $DONATION_DESTINATIONS = [self::DONATION_PREAPPROVED_CHARITIES, self::DONATION_CUSTOM_FUNDRAISER];
    
    const DESCRIPTION_MAX_LENGTH = 1000;

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_campaign';
    }

    public function scenarios() {
        return [
            'default' => ['name', 'alias', 'tiedToParent', 'parentCampaignId', 'description', 'goalAmount', 'streamService', 'type', 'startDate', 'endDate', 'layoutType',
                'paypalAddress', 'donationDestination', 'charityId', 'customCharity', 'customCharityPaypal',
                'channelName', 'channelTeam', 'primaryColor', 'secondaryColor', 'themeId', 'twitterEnable',
                'twitterName', 'facebookEnable', 'facebookUrl', 'youtubeEnable', 'youtubeUrl',
                'enableDonorComments', 'enableThankYouPage', 'thankYouPageText', 'teamEnable', 'formVisibility']
        ];
    }

    public function __construct($config = array()) {
        parent::__construct($config);

        if ($this->isNewRecord) {
            $this->type = self::TYPE_PERSONAL_FUNDRAISER;
            $this->layoutType = self::LAYOUT_TYPE_SINGLE;
            if (isset(\Yii::$app->components['user']) && !\Yii::$app->user->isGuest){
                $this->userId = \Yii::$app->user->id;
            }
        }
    }

    public function init() {
        parent::init();
        $this->type = self::TYPE_PERSONAL_FUNDRAISER;
        $this->formVisibility = true;
        $this->enableDonorComments = true;
        $this->enableThankYouPage = false;
        $this->donationDestination = self::DONATION_PREAPPROVED_CHARITIES;
    }
    
    public function beforeSave($insert) {
        if ($insert) {
            if (isset(\Yii::$app->user) && !\Yii::$app->user->isGuest) {
                $this->userId = \Yii::$app->user->id;
            }
            if (!$this->key) {
                //$this->key can be set only for test events
                $this->key = md5(rand());
            }

            if(($this->type == Campaign::TYPE_CHARITY_FUNDRAISER) && ($this->tiedToParent) && !empty($this->parentCampaignId)){
                $parentCampaign = Campaign::findOne($this->parentCampaignId);
                $this->charityId = $parentCampaign->charityId;
                $this->donationDestination = $parentCampaign->donationDestination;
                $this->customCharity = $parentCampaign->customCharity;
                $this->customCharityPaypal = $parentCampaign->customCharityPaypal;
            }
        }
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($insert){
            if (!$this->parentCampaignId){
                $this->parentCampaignId = $this->id;
                $this->save();
            }
            $streamboardCampaign = new StreamboardCampaign();
            $streamboardCampaign->userId = $this->userId;
            $this->link('streamboard', $streamboardCampaign);
        }
    }
    
    public function attributeLabels() {
        return [
            'type' => 'Type of Campaign',
            'tiedToParent' => 'Tie this event to a fundraiser',
            'description' => 'Campaign Description',
            'layoutType' => 'Type of Layout',
            'channelTeam' => 'Team Channel Name',
            'twitterName' => 'Twitter username',
            'youtubeUrl' => 'YouTube Channel URL',
            'facebookUrl' => 'Facebook page/profile URL'
        ];
    }

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['name', 'nameFilter'],
            ['parentCampaignId', 'parentCampaignIdFilter'],
            ['description', 'filter', 'filter' => 'strip_tags'],
            ['description', 'string', 'length' => [0,self::DESCRIPTION_MAX_LENGTH]],
            ['goalAmount', 'required'],
            ['goalAmount', 'double'],
            ['paypalAddress', 'email'],
            ['googleAnalytics', 'filter', 'filter' => 'strip_tags'],
            ['channelName', 'filter', 'filter' => 'strip_tags'],
            ['channelTeam', 'filter', 'filter' => 'strip_tags'],
            ['twitterName', 'twitterFilter'],
            ['facebookUrl', 'url', 'defaultScheme' => 'http'],
            ['youtubeUrl', 'url', 'defaultScheme' => 'http'],
            ['thankYouPageText', 'thankYouPagePurifier']
        ];
    }
    
    /* @inheritdoc
     * @return query\CampaignQuery
     */
    public static function find()
    {
        return new query\CampaignQuery(get_called_class());
    }
    
    public function nameFilter(){
        $userId = $this->userId;
        if (!$userId){
            $this->addError('userId', "User should be set");
            return;
        }
        if ($this->name){
            $alias = str_replace(' ', '_', $this->name);
            /**better to use userId field that current user, because of possible sample data*/
            $query = Campaign::find()->where(['userId' => $userId,'status' => self::STATUS_ACTIVE, 'alias' => $alias]);
            if ($this->id){
                $query->andWhere(['not','id ='. $this->id]);
            }
            $count = $query->count();
            if ($count>0){
                $this->addError('name','That name already exists');
            } else {
                $this->alias = $alias;
            }
        }
    }
    
    /**
     * Validate parentCampaignId.
     * For a new record with [$this->id] is null [$this->parentCampaignId] can be filtered to null.
     * Later new Campaign in that case it will be updated in [afterSave] method with "$this->parentCampaignId = $this->id"
     */
    public function parentCampaignIdFilter(){
        $id = intval($this->parentCampaignId);
        if ($this->id == $id){
            return;
        }

        if ( ($this->type != Campaign::TYPE_CHARITY_FUNDRAISER) || (!$this->tiedToParent) ){
            $this->parentCampaignId = $this->id;
        }

        /*if Campaign::TYPE_CHARITY_FUNDRAISER and tiedToParent are selected */
        $userId = $this->userId;
        $countInvite = CampaignInvite::find()->where(['userId' => $userId, 'campaignId' => $id, 'status' => CampaignInvite::STATUS_ACTIVE ])->count();
        $countOwnCampaigns = Campaign::find()->where(['userId' => $userId, 'id' => $id, 'status' => Campaign::STATUS_ACTIVE, 'type' => Campaign::TYPE_CHARITY_FUNDRAISER])->count();
        
        /*so there are no such campaign*/
        if ($countInvite === 0 && $countOwnCampaigns === 0){
            $this->parentCampaignId = $this->id;
        }
    }
    
    public function thankYouPagePurifier(){
        if ($this->thankYouPageText){
            $this->thankYouPageText = HtmlPurifier::process($this->thankYouPageText); 
        }
    }
    public function twitterFilter() {
        if ($this->twitterName && $this->twitterName[0] != '@') {
            $this->twitterName = '@' . $this->twitterName;
        }
    }

    public $backgroundImageUrl;
    public $backgroundImageSmallUrl;
    
    /*that is backgroundImage container photo*/
    public $backgroundImage;

    public function afterFind() {
        parent::afterFind();
        $this->refreshBackgroundImageFields();
    }
    
    public function refreshBackgroundImageFields(){
        $id = $this->backgroundImageId;
        if ($id){
            $this->backgroundImageUrl = BaseImage::getOriginalUrlById($id);
            $this->backgroundImageSmallUrl = BaseImage::getMiddleUrlById($id);
        }
    }


    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getTeams() {
        return $this->hasMany(LayoutTeam::className(), ['id' => 'layoutId']);
    }

    public function getTheme() {
        return $this->hasOne(Theme::className(), ['id' => 'themeId']);
    }
    
    public function getCharity() {
        return $this->hasOne(Charity::className(), ['id' => 'charityId']);
    }
    
    public function getParentCampaign() {
        return $this->hasOne(Campaign::className(), ['id' => 'parentCampaignId']);
    }
    
    /**
     * 
     * @return \yii\db\ActiveQuery[]
     */
    public function getDonations(){
        return Donation::find()->where(['campaignId' => $this->id])->orWhere(['parentCampaignId' => $this->id])->andWhere('paymentDate > 0')->orderBy('paymentDate DESC');
    }
    
    public function getChildCampaigns(){
        return Campaign::find()->where(['parentCampaignId' => $this->id, 'status' => self::STATUS_ACTIVE ])
                ->andWhere('id <> ' . $this->id);
    }
           
    /**
     * @return boolean true if that campaign is campaign for which user was invited
     * and that invite is approved by user
     */
    public function isParentForCurrentUser(){
        if ($this->isNewRecord) {
            return false;
        }
        
        $userId = \Yii::$app->user->id;
        $invite = CampaignInvite::findOne(['userId' => $userId, 'campaignId' => $this->id]);
        return $invite && $invite->status == CampaignInvite::STATUS_ACTIVE;
    }
    
    /**
     * @return boolean true if that campaign is child campaign, that mean is tied to another parent campaign
     */
    public function isChild(){
        if ($this->isNewRecord) {
            return false;
        }
        
        return $this->id != $this->parentCampaignId;
    }
    
    public function getDonationEmail(){
        if ($this->isChild()){
            return $this->parentCampaign->donationEmail;
        }
        
        if ($this->type == self::TYPE_PERSONAL_FUNDRAISER){
            return $this->paypalAddress;
        }
        
        if (!$this->donationDestination){
            return '';
        }
        
        if ($this->donationDestination == self::DONATION_CUSTOM_FUNDRAISER){
            return $this->customCharityPaypal;
        }
        
        /* @var $charity Charity */
        $charity = $this->charity;
        return ($charity) ? $charity->paypal : '';
    }
    /**
     * @description Fired after successful donation. It updates current campaign statistics and parent campaign statistics.
     * @param integer $id
     */
    public static function updateDonationStatistics($id){
        $campaign = Campaign::findOne($id);
        
        $sum = Donation::find()->where(['campaignId' => $campaign->id])
                ->orWhere(['parentCampaignId' => $campaign->id])->andWhere('paymentDate > 0')->sum('amount');
        
        $campaign->amountRaised = $sum;
        $donations = $campaign->getDonations()->count('DISTINCT email');
        $campaign->numberOfDonations = $campaign->getDonations()->count('*');
        $campaign->numberOfUniqueDonors = $campaign->getDonations()->count('DISTINCT email');
        $campaign->save();
        if ($campaign->parentCampaignId != $campaign->id ){
            self::updateDonationStatistics($campaign->parentCampaignId);
        }
    }

    /**
     * If campaing is parent it can be at streamboard for a few users. So
     */
    public function getStreamboard(){
        $userId = $this->userId;
        return $this->hasOne(StreamboardCampaign::className(), ['campaignId' => 'id'])->where(['userId' =>  $userId ]);
    }

    public function getFirstGiving() {
        if ($charity = $this->charity) {
            return $charity->firstGiving ? : false;
        }
        return false;
    }
}
