<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;


/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property string $fontSize
 * @property string $fontColor
 * @property string $fontStyle
 * @property string $fontUppercase
 */
class WidgetTagStyle extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_tags_style';
    }

    public function scenarios() {
        return [
            'default' => ['fontStyle', 'fontSize', 'fontWeight', 'fontColor','fontUppercase','width','height','messagePositionX','messagePositionY']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber','fontStyle', 'fontSize', 'fontWeight', 'fontColor','fontUppercase','width','height','messagePositionX','messagePositionY'];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['fontUppercase'] = $this->fontUppercase == 1;
        return $data;
    }
}
