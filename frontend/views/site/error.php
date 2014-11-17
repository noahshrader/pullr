<?php

use yii\helpers\Html;
use common\assets\BigVideoAsset;
/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$this->title = "(╯°□°）╯︵ ┻━┻";
BigVideoAsset::register($this);
$this->registerJsFile('@web/js/site/error.js', [
    'depends' => BigVideoAsset::className()
]);
?>
<div class="missing-wrap">
	<div class="missing animated slideInUp">
		<h2>Oops! That doesn't exist here.</h2>
		<a href="/">Back to Dashboard</a>
	</div>
</div>
<div class="video-overlay"></div>