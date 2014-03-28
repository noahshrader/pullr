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
            $to = explode(';', $mail->to);
            Yii::$app->mail->compose()->setHtmlBody($mail->text)
                ->setFrom([$mail->from => 'Pullr'])
                ->setTo($to)
                ->setSubject($mail->subject)
                ->send(new yii\swiftmailer\Mailer());
            $mail->processingDate = time();
            $mail->save();
        }
    }
}
