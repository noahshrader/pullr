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
        'css/frontend/streamboard/streamboard.less',
    ];

    public $js = [
        '//code.jquery.com/ui/1.10.4/jquery-ui.js',
        '//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js',
        '//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular-touch.min.js',
        'bower_components/venturocket-angular-slider/build/angular-slider.min.js',
    ];
    public $depends = [
    ];
}
