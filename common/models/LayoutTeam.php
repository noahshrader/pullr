<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\Campaign;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class LayoutTeam extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_layoutTeam';
    }
    
     public function rules() {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['youtube', 'filter', 'filter' => 'strip_tags'],
            ['youtube', 'url', 'defaultScheme' => 'http'],
            ['facebook', 'filter', 'filter' => 'strip_tags'],
            ['facebook', 'url', 'defaultScheme' => 'http'],
            ['twitter', 'filter', 'filter' => 'strip_tags'],
            ['twitter', 'url', 'defaultScheme' => 'http'],
        ];
    }
    /**
     * 
     * @return Campaign
     */
    public function getLayout() {
        return $this->hasOne(Campaign::className(), ['id' => 'layoutId']);
    }
}
