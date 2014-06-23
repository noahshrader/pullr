<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class StreamboardAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
            'css/streamboard.less',
            '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css',
	];
        
	public $js = [
//            '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
            '//code.jquery.com/ui/1.10.4/jquery-ui.js',
            'js/streamboard/streamboard.js',
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}
