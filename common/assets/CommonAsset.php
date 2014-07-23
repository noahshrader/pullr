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
        'js/lib/pace.min.js',
        'js/lib/jquery.mCustomScrollbar.js',
        'js/common.js',
        'js/plugins-config.js',
        '//ttv-api.s3.amazonaws.com/twitch.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
