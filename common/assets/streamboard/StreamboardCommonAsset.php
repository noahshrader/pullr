<?php
namespace common\assets\streamboard;

use yii\web\AssetBundle;

/**
 * Class StreamboardCommonAsset used also at source page.
 * @package common\assets\streamboard
 */
class StreamboardCommonAsset extends AssetBundle
{
    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css',
        /*style for venturocket-angular-slider*/
        'css/angular-slider/slider.less',
        'bower_components/bootstrap-select/dist/css/bootstrap-select.min.css',
        'css/frontend/streamboard/streamboard.less',
    ];

    public $js = [
        '//code.jquery.com/ui/1.10.4/jquery-ui.js',
        'bower_components/angular/angular.min.js',
        'bower_components/angular-touch/angular-touch.min.js',
        'bower_components/venturocket-angular-slider/build/angular-slider.min.js',
        'bower_components/bootstrap-select/dist/js/bootstrap-select.min.js',
        'bower_components/angular-bootstrap-select/build/angular-bootstrap-select.min.js',
    ];
    public $depends = [
    ];
}
