<?php
namespace frontend\assets;
use yii\web\AssetBundle;

class StreamboardAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
            '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css',
            'css/streamboard.less',
	];
        
	public $js = [
            '//code.jquery.com/ui/1.10.4/jquery-ui.js',
            '//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js',
            'js/streamboard/streamboard.js',
            'js/streamboard/app.js',
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}
