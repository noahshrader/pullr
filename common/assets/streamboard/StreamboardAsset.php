<?php
namespace common\assets\streamboard;
use yii\web\AssetBundle;

class StreamboardAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
//        'bower_components/seiyria-bootstrap-slider/css/bootstrap-slider.css'
	];
	public $js = [
		'js/streamboard/iframeResizer.js',
		'js/streamboard/streamboard.js',
//        'bower_components/seiyria-bootstrap-slider/js/bootstrap-slider.js'
	];
	public $depends = [
            'common\assets\streamboard\StreamboardCommonAsset',
	];
}
