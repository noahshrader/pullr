<?php
namespace common\assets;
use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle {
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
		'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css',
		'plugins/bootstrapExtend/css/bootstrap-colorpicker.css',
		'css/frontend/frontend.less',
		'css/animate.css'
	];
	public $js = [
		'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
		'plugins/pace/pace.min.js',
		'plugins/datatables/DT_bootstrap.js',
		'plugins/bootstrapExtend/js/bootstrap-colorpicker.js',
		'js/frontend.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'common\assets\CommonAsset'
	];
}
