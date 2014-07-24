<?php
namespace common\assets;

use yii\web\AssetBundle;

/**
 * Asset for Layout view Donation Form.
 */
class DonationAsset extends AssetBundle
{
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
            'css/frontend/donation.less',
	];
        
	public $js = [
            '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
            'js/common.js',
            'js/donation/donation.js',
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}
