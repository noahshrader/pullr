<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Frameworks and libraries used almost throughtout project.
 */
class BaseCommonAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/less/bootstrap.less',
        'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css',
        'plugins/datatables/jquery.dataTables.css',
        'css/animate.css'
    ];
    public $js = [
        '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
        'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
        'plugins/jquery.form/jquery.form.js',
        'plugins/datatables/jquery.dataTables.js',
        'plugins/datatables/DT_bootstrap.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\widgets\Select2Asset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}