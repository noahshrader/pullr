<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\Layout;
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
            ['name', 'filter', 'filter' => 'strip_tags']
        ];
    }
    /**
     * 
     * @return Layout
     */
    public function getLayout() {
        return $this->hasOne(Layout::className(), ['id' => 'layoutId']);
    }
}