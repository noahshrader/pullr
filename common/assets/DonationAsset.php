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
            'js/common.js',
            'js/donation/donation.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'kartik\widgets\Select2Asset'
	];
    public $publishOptions = [
        'forceCopy' => true
    ];
}