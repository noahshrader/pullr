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
		'bower_components/iframe-resizer/js/iframeResizer.contentWindow.min.js',
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/campaigns.js',
        'js/streamboard/angular-app/app-source.js',
        'js/streamboard/angular-app/stream.js',
        'js/streamboard/angular-app/regionsService.js',
        'js/streamboard/angular-app/simple-marquee.js',
        'js/streamboard/angular-app/donationsService.js',
        'js/streamboard/angular-app/streamboardConfig.js',
        'js/streamboard/angular-app/activityFeed.js',
        'js/streamboard/angular-app/activityFeedHelper.js',
	];
	public $depends = [
		'common\assets\streamboard\StreamboardCommonAsset',
	];
}
