<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Payment;
use common\components\Application;
use common\models\base\BaseImage;

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
            'default' => ['name','domain','streamService','type','channelName', 'channelTeam', 'chat', 'chatToggle', 'enableDonations', 
                'primaryColor', 'secondaryColor', 'tertiaryColor']
        ];
    }
    public function __construct($config = array()) {
        parent::__construct($config);
        
        if ($this->isNewRecord){
            $this->type = self::TYPE_SINGLE;
        }
    }
    public function attributeLabels() {
        return [
            'domain' => 'Google Analytics Tracking ID',
            'type' => 'Type of Layout',
            'channelTeam' => 'Team Channel Name',
        ];
    }
    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['domain', 'filter', 'filter' => 'strip_tags'],
            ['channelName', 'filter', 'filter' => 'strip_tags'],
            ['channelTeam', 'filter', 'filter' => 'strip_tags'],
        ];
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
        if ($this->photoId) {
            $id = $this->photoId;
            $this->photo = BaseImage::getOriginalUrlById($id);
            $this->smallPhoto = BaseImage::getMiddleUrlById($id);
        } else {
            $this->photo = BaseImage::NO_PHOTO_LINK();
            $this->smallPhoto = BaseImage::NO_PHOTO_LINK();
        }
    }

    public function beforeSave($insert) {
        if ($insert && isset(\Yii::$app->user) && !\Yii::$app->user->isGuest){
            $this->userId = \Yii::$app->user->id;
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

    public function getTeams(){
        return $this->hasMany(LayoutTeam::className(), ['id' => 'layoutId']);
    }
}
