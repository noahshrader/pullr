<?php

namespace frontend\models\site;

use yii\db\ActiveRecord;
use common\models\User;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class EmailConfirmation extends ActiveRecord {
    const STATUS_SENT = 'sent';
    const STATUS_APPROVED = 'approved';
    
    public static $STATUSES = [self::STATUS_SENT, self::STATUS_APPROVED];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_emailConfirmation';
    }
    
    public static function getKeyForEmail($email, $updateDate = false){
        /*in fact orderBy left for almost impossible situations*/
        $confirmation = EmailConfirmation::find()->where(['status' => self::STATUS_SENT, 'email' => $email])->orderBy('lastSent DESC')->one();
        if (!$confirmation){
            $confirmation = new EmailConfirmation();
            $confirmation->email = $email;
            $confirmation->key = md5(rand());
            $confirmation->userId = \Yii::$app->user->id;
        } 
        if ($updateDate || $confirmation->isNewRecord){
            $confirmation->lastSent = time();
            $confirmation->save();
        }
        
        return $confirmation->key;
    }
   
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
