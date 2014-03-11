<?php

namespace common\assets;

use yii\web\AssetBundle;

class DataTableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/data-tables/DT_bootstrap.less'
    ];
    public $js = [
            'plugins/data-tables/jquery.dataTables.js',
            'plugins/data-tables/DT_bootstrap.js',
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
