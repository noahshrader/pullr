<?php

namespace common\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
            'css/backend/reports.less',
            'css/backend/backend.less'
        ];
	public $js = [];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
