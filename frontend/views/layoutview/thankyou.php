<?php
use common\assets\ThankYouAsset;
use common\models\Campaign;

ThankYouAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
	<head>
		<? $this->head();?>
		<title>Thank You! - Pullr</title>
	</head>

	<body>
		<?php $this->beginBody() ?>
		<? 
			$charityName = '';
			if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
				 $charityName = $campaign->customCharity;
			} 
			if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES && $campaign->charityId){
				 $charityName = $campaign->charity->name;
			} 
		?>
		<div class="form-wrapper thankyou">
			<h1 class="main-title">Thank You!</h1>
			<!--<?= $charityName ?>-->
			<div class="custom-message-wrap">
			<? if ($campaign->enableThankYouPage): ?>
				<?= $campaign->thankYouPageText ?>
			<? else: ?>
				<p>Your donation is appreciated! ^_^</p>
			<? endif ?>
		</div>
		<?php $this->endBody() ?>

		<!-- Footer -->
		<footer id="footer">
			<h5>Powered by</h5>
			<a class="logo mdib-pullr-logo" href="http://www.pullr.io" target="_blank"></a>
		</footer>
	</body>

</html>
<?php $this->endPage() ?>