<?php

namespace common\models;

use yii\base\Model;
use Yii;

/**
 * Login form
 */
class ChangePasswordForm extends Model {

    public $oldPassword;
    public $newPassword;
    public $confirmPassword;
    public $success;

    public function scenarios() {
        return [
            'default' => ['oldPassword', 'newPassword', 'confirmPassword']
        ];
    }

        public function rules() {
            return [
                ['newPassword', 'string', 'min' => 6],
                ['confirmPassword', 'string', 'min' => 6],
            ];
        }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword() {
        $user = \Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', 'Incorrect password.');
        }
    }

    public function validateNewPassword() {
        if (!$this->newPassword) {
            $this->addError('newPassword', 'New password should be set');
        }
        if ($this->newPassword != $this->confirmPassword) {
            $this->addError('confirmPassword', "Passwords doesn't match");
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser() {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

}
