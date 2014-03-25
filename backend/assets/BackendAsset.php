<?php

namespace backend\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
            'css/reports.less',
            'css/backend.less'
        ];
	public $js = [];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
