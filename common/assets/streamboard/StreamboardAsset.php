<?php
namespace common\assets\streamboard;
use yii\web\AssetBundle;

class StreamboardAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
        'bower_components/venturocket-angular-slider/build/angular-slider.min.js',
        'bower_components/bootstrap-select/dist/js/bootstrap-select.min.js',
        'bower_components/angular-bootstrap-select/build/angular-bootstrap-select.min.js',
        'bower_components/ng-file-upload/angular-file-upload.min.js',
        'plugins/iframeResizer/iframeResizer.js',
		'js/streamboard/streamboard.js',
        /**angular-app begin*/
        'js/streamboard/angular-app/donations.js',
        'js/streamboard/angular-app/stream.js',
        'js/streamboard/angular-app/regionsService.js',
        'js/streamboard/angular-app/regionsPanels.js',
        'js/streamboard/angular-app/regionsConfigs.js',
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/campaigns.js',
        'js/streamboard/angular-app/settings.js',
        'js/streamboard/angular-app/alertMediaManager.js',
        'js/streamboard/angular-app/app.js',
        /*angular-app end*/
	];
	public $depends = [
            'common\assets\streamboard\StreamboardCommonAsset',
	];
}
