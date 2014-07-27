<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$this->title = $name;
$this->registerJsFile('http://vjs.zencdn.net/c/video.js', common\assets\CommonAsset::className());
$this->registerJsFile('@web/js/lib/bigvideo.js', common\assets\CommonAsset::className());
$this->registerJsFile('@web/js/lib/bv-config.js', common\assets\CommonAsset::className());
?>
<div id="content" class="error">
	<div class="site-error"></div>
	<div class="site-error-msg">
		<h1>Can't Find that</h1>
	</div>
</div>