<?php

namespace frontend\models;

use common\models\User;
use yii\base\Model;
use common\models\mail\Mail;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => '\common\models\User', 'targetAttribute' => 'login', 'message' => 'There is no user with such login.'],
        ];
    }

    /**
     *
     * @return boolean sends an email
     */
    public function sendEmail() {
        /** @var User $user */
        $user = User::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'login' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        $user->setScenario('password_reset_token');
        $user->generatePasswordResetToken();
        
        if ($user->save()) {
            $controller = new \yii\web\Controller(-1,'main');
            $content = $controller->renderPartial('@console/views/mail/passwordReset', [
                'passwordResetToken' => $user->password_reset_token
            ]);
            
            Mail::sendMail($this->email, 'Password Reset', $content, 'passwordReset');
            return true;
        }

        return false;
    }

}
