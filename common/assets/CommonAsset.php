<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/common.less'
    ];
    public $js = [
        'js/common.js',
        'js/pace.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
