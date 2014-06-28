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
        'js/pace.min.js',
        'js/jquery.mCustomScrollbar.js',
        'js/common.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
