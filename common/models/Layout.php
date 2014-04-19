<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Theme;

/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Layout extends ActiveRecord {

    const STATUS_ACTIVE = 'active';
//    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_DELETED];

    const STREAM_SERVICE_TWITCH = 'Twitch';
    const STREAM_SERVICE_HITBOX = 'Hitbox';

    public static $STREAM_SERVICES = [self::STREAM_SERVICE_TWITCH, self::STREAM_SERVICE_HITBOX];

    const TYPE_SINGLE = 'Single Stream';
    const TYPE_TEAM = 'Team Stream';
    const TYPE_MULTI = 'Multi Stream';

    public static $TYPES = [self::TYPE_SINGLE, self::TYPE_TEAM, self::TYPE_MULTI];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_layout';
    }

    public function scenarios() {
        return [
            'default' => ['name', 'domain', 'streamService', 'type', 'channelName', 'channelTeam', 'chat', 'chatToggle', 'enableDonations',
                'primaryColor', 'secondaryColor', 'tertiaryColor', 'themeId', 'twitterEnable', 'twitterName', 'facebookEnable', 'facebookUrl',
                'youtubeEnable', 'youtubeUrl', 'includeYoutubeFeed']
        ];
    }

    public function __construct($config = array()) {
        parent::__construct($config);

        if ($this->isNewRecord) {
            $this->type = self::TYPE_SINGLE;
        }
    }

    public function attributeLabels() {
        return [
            'domain' => 'Google Analytics Tracking ID',
            'type' => 'Type of Layout',
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
            ['domain', 'filter', 'filter' => 'strip_tags'],
            ['channelName', 'filter', 'filter' => 'strip_tags'],
            ['channelTeam', 'filter', 'filter' => 'strip_tags'],
            ['twitterName', 'twitterFilter'],
            ['facebookUrl', 'url', 'defaultScheme' => 'http'],
            ['youtubeUrl', 'url', 'defaultScheme' => 'http']
        ];
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
    
    public function getEvent() {
        return $this->hasOne(Event::className(), ['id' => 'eventId']);
    }
    
    

}
