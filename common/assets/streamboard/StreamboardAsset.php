<?php
namespace common\assets\streamboard;
use yii\web\AssetBundle;

class StreamboardAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
        'bower_components/angular-bootstrap-colorpicker/css/colorpicker.css',
	];
	public $js = [
        '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js',
        'bower_components/venturocket-angular-slider/build/angular-slider.min.js',
        'bower_components/select2/select2.min.js',
        'bower_components/angular-ui-select2/src/select2.js',
        'bower_components/ng-file-upload/angular-file-upload.min.js',
        'bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js',
        'bower_components/angular-bootstrap/ui-bootstrap.min.js',
        'bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
        'bower_components/iframe-resizer/js/iframeResizer.min.js',
        'bower_components/jQuery.Marquee/jquery.marquee.min.js',
        'bower_components/moment/moment.js',
        'bower_components/angular-moment/angular-moment.min.js',
        'js/streamboard/angular-app/angular-marquee.js',
		'js/streamboard/streamboard.js',
        /* angular-app begin */
        'js/streamboard/angular-app/donationsService.js',
        'js/streamboard/angular-app/rotatingMessages.js',
        'js/streamboard/angular-app/donationsCtrl.js',
        'js/streamboard/angular-app/stream.js',
        'js/streamboard/angular-app/regionsService.js',
        'js/streamboard/angular-app/regionsPanels.js',
        'js/streamboard/angular-app/regionsConfigs.js',
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/campaigns.js',
        'js/streamboard/angular-app/settings.js',
        'js/streamboard/angular-app/alertMediaManager.js',
        'js/streamboard/angular-app/app.js'
        /* angular-app end */
	];
	public $depends = [
            'common\assets\streamboard\StreamboardCommonAsset'
	];
}
