<?php

namespace common\models\mail;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Charity;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Mail extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_mail';
    }
    
    public static function sendMail($to, $subject, $content, $type = null, $from = null){
        if ($from == null){
            $from = \Yii::$app->params['mailFrom'];
        }
        $controller = new \yii\base\Controller(-1,'test');
        $params = [
            'content' => $content,
            'footer' => '<br /> Do not reply for this email'
        ];
        $text = $controller->getView()->render('@console/views/mail/layout', $params, $controller);
        
        if (is_array($to)){
            $to = implode(';', $to);
        }
        $mail = new Mail();
        $mail->to = $to;
        $mail->from = $from;
        $mail->subject = $subject;
        $mail->text = $text;
        $mail->type = $type;
        $mail->save();
    }
}
