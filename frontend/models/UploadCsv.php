<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadCsv extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;
    
    public $campaignId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
            ['campaignId', 'required']
        ];
    }
} 