<?php

namespace common\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
            'css/backend/backend.less',
            'css/backend/reports.less'
        ];
	public $js = [
        'js/backend.js'
    ];
	public $depends = [
        'yii\web\YiiAsset',
        'common\assets\CommonAsset'
	];
}
