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
	];
	public $depends = [
                'common\assets\streamboard\StreamboardCommonAsset',
	];
}
