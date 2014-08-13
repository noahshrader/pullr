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
        'plugins/iframeResizer/iframeResizer.js',
		'js/streamboard/streamboard.js',
        /**angular-app begin*/
        'js/streamboard/angular-app/donations.js',
        'js/streamboard/angular-app/regions.js',
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/app.js',
        /*angular-app end*/
	];
	public $depends = [
            'common\assets\streamboard\StreamboardCommonAsset',
	];
}
