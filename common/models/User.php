<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use common\components\Application;
use common\models\Notification;
use common\models\base\BaseImage;
use common\models\Plan;
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
     * that field is used to upload photo for user avatar
     * @var type 
     */
    public $images;
    /**
     * @var string the raw password. Used to collect password input and isn't saved in database
     */
    public $password;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

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
    
    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id) {
        return static::find($id);
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
        return Security::validatePassword($password, $this->password_hash);
    }

    public function rules() {
        $default = [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
        ];

        if ($this->getScenario() == 'openId') {
            return $default;
        }
        $additionalRules = [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'],
            ['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
        $result = array_merge($default, $additionalRules);
        return $result;
    }

    public function scenarios() {
        return [
            'photo' => ['photo', 'smallPhoto'],
            'settings' => ['fullName', 'timezone'],
            'openId' => ['name', 'email', 'birthday', 'photo', 'smallPhoto'],
            'signup' => ['name', 'email', 'password', '!status'],
            'roles' => ['role'],
            'resetPassword' => ['password'],
            'requestPasswordResetToken' => ['email'],
            'password_reset_token' => ['password_reset_token'],
            'last_login' => ['last_login'],
        ];
    }

    public function setNewPassword($password){
        $this->password_hash = Security::generatePasswordHash($password);
    }
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
                $this->setNewPassword($this->password);
            }
            if ($this->isNewRecord) {
                $this->auth_key = Security::generateRandomKey();
                
            }
            
            if (!$this->isNewRecord && (!in_array($this->getScenario(), ['photo', 'openId']))){
                if ($this->photo != $this->oldAttributes['photo']){
                    $this->photo = $this->oldAttributes['photo'];
                }
                if ($this->smallPhoto != $this->oldAttributes['smallPhoto']){
                    $this->smallPhoto = $this->oldAttributes['smallPhoto'];
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 
     * @param type $insert  - if true - new record inserted
     */
    public function afterSave($insert) {
        parent::afterSave($insert);
        if ($insert){
            /** so we have new record*/
            $notification = new Notification();
            $notification->userId = $this->id;
            $notification->save();
            
            $plan = new Plan();
            $plan->id = $this->id;
            $plan->plan = Plan::PLAN_BASE;
            $plan->save();
        }
    }
    
    public function getUrl() {
        $url = 'user/' . $this->id;
        if (Application::IsBackend()) {
            return Application::frontendUrl($url);
        } else {
            return $url;
        }
    }

    public function toArray() {
        $allowed = ['id', 'name', 'photo', 'smallPhoto'];
        return array_intersect_key(parent::toArray(), array_flip($allowed));
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->checkPhotoLinks();
    }
    
    public function checkPhotoLinks(){
        if (intval($this->photo)){
            $id = intval($this->photo);
            $this->photo = BaseImage::getOriginalUrlById($id);
            $this->smallPhoto = BaseImage::getMiddleUrlById($id);
        }
    }
        
//    /**
//     * If object created from another object like event->user it will have wrong link to images 
//     * if no getPhoto() and getSmallPhoto is called
//     */
//    public function getPhoto(){
//        $this->checkPhotoLinks();
//    }
//    
//    public function getSmallPhoto(){
//        $this->checkPhotoLinks();
//    }
    
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

        return User::find([
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
        
        public static function findIdentityByAccessToken($token) {
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
        
        
        private $_plan = null;
        public function getPlan(){
            if (!$this->_plan){
                $plan = Plan::find($this->id);
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
            $plan = Plan::find($this->id);
            $plan->prolong($amount);
        }
}
