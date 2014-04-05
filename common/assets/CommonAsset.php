<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/less/bootstrap.less',
        'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css',
        'css/common.less',
        'css/reports.less'
    ];
    public $js = [
        'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
        'plugins/jquery.form/jquery.form.js',
        'https://www.google.com/jsapi',
        'js/common.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
