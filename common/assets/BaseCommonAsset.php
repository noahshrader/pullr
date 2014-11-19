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
        'css/bootstrap/less/bootstrap.less'
    ];
    public $js = [
        '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
        'plugins/jquery.form/jquery.form.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'kartik\widgets\Select2Asset',
        'yii\bootstrap\BootstrapAsset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}