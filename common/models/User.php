<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use common\components\Application;

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

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\AutoTimestamp',
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
            'openId' => ['name', 'email'],
            'signup' => ['name', 'email', 'password', '!status'],
            'roles' => ['role'],
            'resetPassword' => ['password'],
            'requestPasswordResetToken' => ['email'],
            'password_reset_token' => ['password_reset_token']
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (($this->isNewRecord || $this->getScenario() === 'resetPassword') && !empty($this->password)) {
                $this->password_hash = Security::generatePasswordHash($this->password);
            }
            if ($this->isNewRecord) {
                $this->auth_key = Security::generateRandomKey();
            }
            return true;
        }
        return false;
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

}
