<?php
namespace common\assets;
use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle {
	public $sourcePath = '@common/web';
	public $baseUrl = '@web';
	public $css = [
		'plugins/bootstrapSwitch/css/bootstrap3/bootstrap-switch.min.css',
		'plugins/datatables/jquery.dataTables.css',
		'css/frontend/frontend.less',
		'css/animate.css'
	];
	public $js = [
		'plugins/bootstrapSwitch/js/bootstrap-switch.min.js',
		'plugins/pace/pace.min.js',
		'plugins/datatables/jquery.dataTables.js',
		'plugins/datatables/DT_bootstrap.js',
		'js/frontend.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'common\assets\CommonAsset'
	];
}
