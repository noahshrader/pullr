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
        $userId = \Yii::$app->user->id;
        $config = StreamboardConfig::findOne($userId);
        if (!$config){
            $config = new StreamboardConfig();
            $config->userId = $userId;
            $config->save();
        }
        return $config;
    }
}
