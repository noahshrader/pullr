<?php

namespace common\assets;

use yii\web\AssetBundle;

class BigVideoAsset extends AssetBundle{

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';

    public $css = [
        '//vjs.zencdn.net/4.6/video-js.css',
    ];

    public $js = [
        '//vjs.zencdn.net/4.6/video.js',
        'plugins/bigvideo/bigvideo.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
} 