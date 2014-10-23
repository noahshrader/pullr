<?php
namespace common\assets;
use yii\web\AssetBundle;

class ThankYouAsset extends AssetBundle {
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
		'css/frontend/donation/donation.css'
	];
}
