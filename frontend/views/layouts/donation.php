<?php
use yii\helpers\Html;
use common\assets\DonationAsset;
/**
 * That is layout for Donation Form. Which is loaded by Magnific popup
 * @var \yii\web\View $this
 * @var string $content
 */
DonationAsset::register($this);

$campaign = \Yii::$app->controller->campaign;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<?= $this->render('donationHead') ?>
	<body class="campaignflow">
		<? if($campaign->enableDonationProgressBar):?>
	 	<!-- BEGIN Progress Bar -->
		<div class="form-progress" data-amountraised="<?= $campaign->amountRaised ?>" data-goalamount = <?= $campaign->goalAmount ?>>
			<div class="form-progress-wrap">
				<div class="progress" style="width:<?= 100*$campaign->amountRaised/max(1,$campaign->goalAmount) ?>%;" ng-cloak></div>
			</div>
			<div class="totals">
				<span class="total amountRaised" ng-cloak>$<?= number_format($campaign->amountRaised) ?></span> of <span class="goal">$<?= number_format($campaign->goalAmount) ?></span>
			</div>
		</div>
	 	<!-- ENG Progress Bar -->
		<? endif;?>
			 
		<?php $this->beginBody() ?>
			<?= $content ?>
		<?php $this->endBody() ?>
			 
		<!-- Footer -->
		<footer id="footer">
				<h5>Powered by</h5>
				<a class="logo icon-pullr-logo" href="http://www.pullr.io" target="_blank"></a>
		</footer>
		<script type='text/javascript'>
				Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
		</script>
	</body>
</html>
<?php $this->endPage() ?>