<?php

namespace common\assets;

use yii\web\AssetBundle;

class BaseCommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/less/bootstrap.less',
        'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css',
        '//cdn.datatables.net/1.10.0/css/jquery.dataTables.css',
    ];
    public $js = [
        '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
        'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
        'plugins/jquery.form/jquery.form.js',
        '//cdn.datatables.net/1.10.0/js/jquery.dataTables.js',
        'plugins/data-tables/DT_bootstrap.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
