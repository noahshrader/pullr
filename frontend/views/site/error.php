<?php

use yii\helpers\Html;
use common\assets\BigVideoAsset;
/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$this->title = "Oops! We Can't Find That.";
BigVideoAsset::register($this);
$this->registerJsFile('@web/js/site/error.js', [
    'depends' => BigVideoAsset::className()
]);
?>
<div id="content">
</div>