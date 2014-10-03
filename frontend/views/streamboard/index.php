<?
use yii\web\View;

/**@var $this View */
/**@var $regionsNumber integer */
?>
<div ng-app="streamboardApp" class="streamboardContainer">
	<?= $this->render('streamboard-js-variables') ?>
	<?= $this->render('region/regions', ['regionsNumber' => $regionsNumber]) ?>
	<?= $this->render('sidepanel', ['regionsNumber' => $regionsNumber]) ?>
</div>