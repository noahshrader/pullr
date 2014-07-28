<?php

use yii\helpers\Html;
use common\assets\BigVideoAsset;
/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$this->title = $name;
BigVideoAsset::register($this);
$this->registerJsFile('@web/js/site/error.js', BigVideoAsset::className());
?>
<div id="content" class="error">
	<div class="site-error"></div>
	<div class="site-error-msg">
		<h3>Oops! We were unable to locate that :(</h3>
	</div>
</div>