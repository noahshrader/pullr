<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\mail\Mail;


class MailController extends Controller {
    public function actionSend(){
        $mails = Mail::find()->where(['processingDate' => null])->all();
        if (sizeof($mails) == 0) {
            return;
        }
        foreach ($mails as $mail){
            Yii::$app->mail->compose('@console/views/mail/layout')
                ->setFrom($mail->from)
                ->setTo($mail->to)
                ->setSubject($mail->subject)
                ->send(new yii\swiftmailer\Mailer());
        }
    }
}
