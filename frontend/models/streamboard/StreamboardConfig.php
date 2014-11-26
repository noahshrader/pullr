<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * Class StreamboardConfig is used to store user's settings for streamboard.
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $streamboardWidth
 * @property integer $streamboardHeight
 * @property integer $streamboardLeft
 * @property integer $streamboardTop
 * @property integer $clearedDate - last time "clear" button was clicked, by default it is equal to user registration date
 * @property integer $streamRequestLastDate - last time "donations/followers/subscriber" were requested to be showed in the region
 */
class StreamboardConfig extends ActiveRecord {
    const DEFAULT_WIDTH = 1024;
    const DEFAULT_HEIGHT = 728;

    public function init(){
        parent::init();
        $this->streamboardWidth = self::DEFAULT_WIDTH;
        $this->streamboardHeight = self::DEFAULT_HEIGHT;
        $this->streamboardLeft = 0;
        $this->streamboardTop = 0;
        $this->createRegionToken();   
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_config';
    }

    /**
     * @return StreamboardConfig for current user
     */
    public static function get(){
        /**
         * @var User $user
         */
        $user = \Yii::$app->user->identity;
        return $user->streamboardConfig;
    }

    public function createRegionToken() {
        $this->streamboardToken = \Yii::$app->getSecurity()->generateRandomString(12);        
    }
}
