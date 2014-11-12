<?php
namespace common\assets;
use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {
    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/common.less',        
    ];
    public $js = [
        'plugins/jquery.mousewheel/jquery.mousewheel.min.js',
        'plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.js',
        'js/common.js',
        'js/plugins-config.js',
        '//ttv-api.s3.amazonaws.com/twitch.min.js'
    ];
    public $depends = [
        'common\assets\BaseCommonAsset'
    ];
    public $publishOptions = [
        'forceCopy' => true
    ];
}
