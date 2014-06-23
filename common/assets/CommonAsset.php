<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/less/bootstrap.less',
        'css/common.less',
        'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css'
    ];
    public $js = [
        'js/common.js',
        '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
        'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
        'plugins/jquery.form/jquery.form.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
