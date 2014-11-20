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
	<div class="missing">
		<h2>Oops! We can't find that.</h2>
		<a class="btn btn-default" href="/">Go to Dashboard</a>
	</div>
</div>
<div class="video-overlay"></div>