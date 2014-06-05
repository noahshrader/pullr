<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Theme;
use yii\helpers\HtmlPurifier;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Campaign extends ActiveRecord {

    const STATUS_ACTIVE = 'active';
//    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_DELETED];

    const STREAM_SERVICE_TWITCH = 'Twitch';
    const STREAM_SERVICE_HITBOX = 'Hitbox';

    public static $STREAM_SERVICES = [self::STREAM_SERVICE_TWITCH, self::STREAM_SERVICE_HITBOX];
    
    const TYPE_PERSONAL_TIP_JAR = 'Personal Tip Jar';
    const TYPE_CHARITY_EVENT = 'Charity Event';
    const TYPE_CHARITY_FUNDRAISER = 'Charity Fundraiser';
    public static $TYPES = [self::TYPE_PERSONAL_TIP_JAR, self::TYPE_CHARITY_EVENT, self::TYPE_CHARITY_FUNDRAISER];
    
    const LAYOUT_TYPE_SINGLE = 'Single Stream';
    const LAYOUT_TYPE_TEAM = 'Team Stream';
    const LAYOUT_TYPE_MULTI = 'Multi Stream';

    public static $LAYOUT_TYPES = [self::LAYOUT_TYPE_SINGLE, self::LAYOUT_TYPE_TEAM, self::LAYOUT_TYPE_MULTI];

//    const DONATION_PERSONAL_PAYPAL = 'Personal Paypal';
    const DONATION_PREAPPROVED_CHARITIES = 'Pre-approved Charities';
    const DONATION_CUSTOM_FUNDRAISER = 'Custom Fundraiser';
    
    public static $DONATION_DESTINATIONS = [self::DONATION_PREAPPROVED_CHARITIES, self::DONATION_CUSTOM_FUNDRAISER];
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_campaign';
    }

    public function scenarios() {
        return [
            'default' => ['name', 'alias', 'goalAmount', 'enableGoogleAnalytics', 'googleAnalytics', 'streamService', 'type', 'startDate', 'endDate', 'layoutType',
                'paypalAddress', 'donationDestination', 'charityId', 'customCharity', 'customCharityPaypal', 'customCharityDescription', 'enableGoogleAnalytics',
                'channelName', 'channelTeam', 'chat', 'chatToggle', 'enableCustomLogo',
                'primaryColor', 'secondaryColor', 'tertiaryColor', 'themeId', 'twitterEnable', 'twitterName', 'facebookEnable', 'facebookUrl',
                'youtubeEnable', 'youtubeUrl', 'includeYoutubeFeed',
                'enableDonorComments', 'enableThankYouPage', 'thankYouPageText']
        ];
    }

    public function __construct($config = array()) {
        parent::__construct($config);

        if ($this->isNewRecord) {
            $this->type = self::TYPE_PERSONAL_TIP_JAR;
            $this->layoutType = self::LAYOUT_TYPE_SINGLE;
            if (isset(\Yii::$app->components['user']) && !\Yii::$app->user->isGuest){
                $this->userId = \Yii::$app->user->id;
            }
        }
    }

    public function init() {
        parent::init();
        $this->type = self::TYPE_PERSONAL_TIP_JAR;
        $this->formVisibility = true;
        $this->enableDonorComments = true;
        $this->enableThankYouPage = false;
        
    }
    
    public function attributeLabels() {
        return [
            'googleAnalytics' => 'Google Analytics Tracking ID',
            'type' => 'Type of Campaign',
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
     * @return CommentQuery
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

    /**
     * that field is used to upload photo for user avatar
     * @var type 
     */
    public $images;
    public $photo;
    public $smallPhoto;

    public function afterFind() {
        parent::afterFind();
        \common\components\UploadImage::ApplyLogo($this);
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
        }
        return parent::beforeSave($insert);
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
    
    

}
