<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * That is for login page. 
 */
class AuthAsset extends AssetBundle {

    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/auth/login.less',
        'css/animate.css'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\BaseCommonAsset',
    ];
}
