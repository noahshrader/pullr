<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class DonationAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
            'css/donation.less',
            'js/lib/magnificpopup.css'
	];
        
	public $js = [
            '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
            'js/donation/donation.js',
            'js/lib/magnificpopup.js'
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}
