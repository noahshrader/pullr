<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/common.css',
        'css/bootstrap/less/bootstrap.less',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
