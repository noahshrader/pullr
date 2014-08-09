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
        'plugins/bootstrapExtend/css/bootstrap-select.css',
        'plugins/datatables/jquery.dataTables.css',
    ];
    public $js = [
        '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
        'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
        'plugins/bootstrapExtend/js/bootstrap-select.js',
        'plugins/jquery.form/jquery.form.js',
        'plugins/datatables/jquery.dataTables.js',
        'plugins/datatables/DT_bootstrap.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        /*we clearing css loading from yii\boostrap\BootstrapAsset at @common/config/main.php, but dependant assets will work correctly.
        E.g. kartik\widgets\Select2Asset will load select2-bootstrap3.css file*/
        'yii\bootstrap\BootstrapAsset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
