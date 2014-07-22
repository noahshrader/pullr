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
            'js/streamboard/streamboard.js',
	];
	public $depends = [
                'common\assets\streamboard\StreamboardCommonAsset',
	];
}
