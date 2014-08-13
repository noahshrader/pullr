<?php
namespace common\assets\streamboard;
use yii\web\AssetBundle;

class StreamboardSourceAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
        'css/frontend/streamboard/streamboard-source.less',
	];
	public $js = [
		'plugins/iframeResizer/iframeResizer.contentWindow.js',
        /*angular app begin*/
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/campaigns.js',
        'js/streamboard/angular-app/app-source.js',
        /*angular app end*/
	];
	public $depends = [
		'common\assets\streamboard\StreamboardCommonAsset',
	];
}
