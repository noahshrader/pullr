<?php

namespace common\assets;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle{

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';

    public $css = [
        'css/select2/select2.css'
    ];

    public $js = [
        'js/select2/select2.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
} 