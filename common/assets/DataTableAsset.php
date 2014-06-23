<?php

namespace common\assets;

use yii\web\AssetBundle;

class DataTableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdn.datatables.net/1.10.0/css/jquery.dataTables.css'
    ];
    public $js = [
            '//cdn.datatables.net/1.10.0/js/jquery.dataTables.js',
            'plugins/data-tables/DT_bootstrap.js',
    ];
    public $depends = [
        'common\assets\CommonAsset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
