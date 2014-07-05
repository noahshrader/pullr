<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;
use common\models\Notification;
use common\models\base\BaseImage;
use common\models\Plan;
use common\models\Campaign;
use common\models\user\UserFields;

/**
 * Class User
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface {
    /**
     * @var string the raw password. Used to collect password input and isn't saved in database
     */
    public $password;
    
    /**
     * @var string to confirm password. Used to collect confirm password while registering and isn't saved in database
     */
    public $confirmPassword;
    
    
    const STATUS_DELETED = 'deleted';
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    /*user expecting confirmation of email*/
    const ROLE_ONCONFIRMATION = 'onconfirmation';
    
    public $passwordHashStrategy = 'password_hash';
    
    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_PENDING];
    public static $ROLES = [self::ROLE_ONCONFIRMATION, self::ROLE_USER, self::ROLE_ADMIN];

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }
    
    public static function tableName() {
        return 'tbl_user';
    }
    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id) {
        return static::find()->andWhere(['id' => $id])->one();
    }

    /**
     * Finds user by name
     *
     * @param string $name
     * @return null|User
     */
    public static function findByUsername($name) {
        return static::find(['name' => $name, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * @return int|string|array current user ID
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
//        $security = new Security();
//        $security->
        $security = \Yii::$app->security;
        return $security->validatePassword($password, $this->password_hash);
    }

    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'required'],
            ['login', 'email'],
            ['login', 'verifyLogin', 'on' => 'signup'],
            ['login', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            /*that is workaround to call verifyName even if name is empty*/
            ['login', 'verifyName'],
            ['uniqueName', 'verifyUniqueName'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['password', 'required'],
            ['password', 'string', 'min' => 8],
            ['confirmPassword','required'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function scenarios() {
        return [
            'photo' => ['photo', 'smallPhoto'],
            'settings' => ['fullName', 'timezone'],
            'openId' => ['name', 'email', 'birthday', 'photo', 'smallPhoto'],
            'signup' => ['login', 'name', 'email',  'password', 'confirmPassword', '!status', '!role'],
            'emailConfirm' => ['role', 'email'],
            'roles' => ['role'],
            'resetPassword' => ['password', 'password_reset_token'],
            'requestPasswordResetToken' => ['email'],
            'password_reset_token' => ['password_reset_token'],
            'last_login' => ['last_login'],
            'status' => ['status'],
            'adminEdit' => ['login', 'name', 'email', 'role','status'],
            'changePassword' => ['password', 'confirmPassword']
        ];
    }
    
    public function attributeLabels() {
        return [
            'login' => 'Login (email)',
        ];
    }
    public function verifyUniqueName(){
        if ($this->uniqueName && ($this->isNewRecord || $this->uniqueName != $this->oldAttributes['uniqueName'])){
            $user = User::findOne(['uniqueName' => $this->uniqueName]);
            if ($user){
                $this->uniqueName = ($this->isNewRecord)? '': $this->oldAttributes['uniqueName'];
            }
        }
    }
    
    public function getUrl(){
        if ($this->uniqueName && !(ctype_digit($this->uniqueName))){
            return $this->uniqueName.'/'; 
        } else {
            return $this->id.'/';
        }
    }
    
    public static function getUserByUrl(){
        
    }
    
    public function verifyLogin(){
        $count = User::find()->where(['status' => User::STATUS_ACTIVE, 'login' => $this->login])->count();
        if ($count>0){
            $this->addError('login','This email address has already been taken.');
        }
    }
    public function verifyName(){
        if (!$this->name){
            $this->name = $this->login;
        }
    }
    
    public function setNewPassword($password){
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
                $this->setNewPassword($this->password);
            }
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomKey();
                
            }
            
            if (strpos(BaseImage::NO_PHOTO_PATH, $this->photo)!==false){
                $this->photo = null;
            } 
            if (strpos(BaseImage::NO_PHOTO_PATH, $this->smallPhoto)!==false){
                $this->smallPhoto = null;
            } 
            return true;
        }
        return false;
    }

    /**
     * 
     * @param type $insert  - if true - new record inserted
     */
    public function afterSave($insert, $params = array()) {
        parent::afterSave($insert, $params);
        if ($insert){
            /** so we have new record*/
            $notification = new Notification();
            $notification->userId = $this->id;
            $notification->save();
            
            $plan = new Plan();
            $plan->id = $this->id;
            $plan->plan = Plan::PLAN_BASE;
            $plan->save();
            
            $userFields = new UserFields();
            $userFields->userId = $this->id;
            $userFields->systemNotificationDate = time();
            $userFields->save();
        }
    }
    
    public function toArray(array $fields = [], array $expand = [], $recursive = true) {
        $allowed = ['id', 'name', 'photo', 'smallPhoto'];
        return array_intersect_key(parent::toArray($fields, $expand, $recursive), array_flip($allowed));
    }
    
    
    /**
     * also send user email (private information)
     * @return type
     */
    public function toArrayPrivate(array $fields = [], array $expand = [], $recursive = true){
        $allowed = ['id', 'name', 'photo', 'smallPhoto', 'email'];
        return array_intersect_key(parent::toArray($fields, $expand, $recursive), array_flip($allowed));
    
    }
    
    /**
     * that field is used to upload photo for user avatar
     * @var type 
     */
    public $images;
    
    public function afterFind() {
        parent::afterFind();
        \common\components\UploadImage::ApplyLogo($this);
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return self
     */
    public static function findByPasswordResetToken($token) {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return User::findOne([
                    'password_reset_token' => $token,
                    'status' => User::STATUS_ACTIVE,
        ]);
    }
    
    
	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Security::generateRandomKey() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}
        
        public function getUsername(){
            return $this->name;
        }
        
        public static function findIdentityByAccessToken($token,$type = null) {
            return null;
        }
        /**
        * 
        * @return User
        */
        public function getNotification() {
            return $this->hasOne(Notification::className(), ['userId' => 'id']);
        }
        
        /**
         * 
         * @return User
         */
        public function getOpenIDToUser() {
            return $this->hasOne(OpenIDToUser::className(), ['userId' => 'id']);
        }
        
        /**
         * return ActiveQuery object for parent campaigns for current user
         */
        public function getParentCampaigns($status = Campaign::STATUS_ACTIVE){
            $userId = \Yii::$app->user->id;
            $parentIds = CampaignInvite::find()->where(['userId' => $userId, 'status' => CampaignInvite::STATUS_ACTIVE ])->select(['campaignId'])->column();
            return Campaign::find()->where(['in', 'id', $parentIds])->andWhere(['status' => $status]);
        }
        
        public function getCampaigns($status = Campaign::STATUS_ACTIVE){
            $userId = \Yii::$app->user->id;
            $userCampaigns = Campaign::find()->where(['userId' => $userId, 'status' => $status]);
            
            return $userCampaigns->union($this->getParentCampaigns($status));
        }
        
        private $_plan = null;
        /*
         * @return Plan::PLAN_BASE or Plan::PLAN_PRO
         */
        public function getPlan(){
            if (!$this->_plan){
                $plan = Plan::findOne($this->id);
                $this->_plan = $plan->plan;
                if ($plan->expire < time()){
                    $this->_plan = Plan::PLAN_BASE;
                }
            }
            
            return $this->_plan;
        }
        
        /**
         * prolong User plan
         * @param type $amount - money amount
         */
        public function prolong($amount){
            $plan = Plan::findOne($this->id);
            $plan->prolong($amount);
        }
}
