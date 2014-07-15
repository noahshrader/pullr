<?php
namespace common\assets;
use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
            'css/frontend/frontend.less'
	];
	public $js = [
        'js/frontend.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
        'common\assets\CommonAsset'
	];
}
