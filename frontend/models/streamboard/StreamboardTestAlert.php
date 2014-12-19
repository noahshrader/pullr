<?php

namespace frontend\models\streamboard;


use yii\db\ActiveRecord;


class StreamboardTestAlert extends ActiveRecord {

    public static function tableName()
    {
        return 'tbl_streamboard_test_alert';
    }

    public function scenarios()
    {
        return [
            'default' => ['alertType', 'regionNumber', 'createdAt', 'userId']
        ];
    }

    public function fields()
    {
        return ['id', 'alertType', 'regionNumber', 'createdAt', 'userId'];
    }
}