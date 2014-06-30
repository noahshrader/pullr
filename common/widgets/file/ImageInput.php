<?php
namespace common\widgets\file;

use yii\bootstrap\Widget;
use kartik\widgets\FileInput;

class ImageInput extends Widget{
    public $name = 'images[]';
    
    public function run(){
        $params = ['multiple' => false, 'accept' => 'image/*'];
        return FileInput::widget([
                        'name' => $this->name,
                        'options' => $params,
                        'pluginOptions' => [
                            'showUpload' => true,
                             'browseLabel' => ' ',
                            'uploadLabel' => ' ',
                            'showRemove' => false,
                        ]]);
    }
}
