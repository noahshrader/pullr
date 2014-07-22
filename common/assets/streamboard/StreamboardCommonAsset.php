<?php
namespace common\assets\streamboard;
use yii\web\AssetBundle;

/**
 * Class StreamboardCommonAsset used also at source page.
 * @package common\assets\streamboard
 */
class StreamboardCommonAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
            '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css',
            'css/frontend/streamboard/streamboard.less',
	];
        
	public $js = [
            '//code.jquery.com/ui/1.10.4/jquery-ui.js',
            '//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js',
            'js/streamboard/app.js',
	];
	public $depends = [
	];
}
