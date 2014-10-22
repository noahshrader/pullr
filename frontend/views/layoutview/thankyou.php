<?php
use common\assets\ThankYouAsset;
ThankYouAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
	<head>
        <? $this->head();?>
        <title>Thank you</title>
	</head>

	<body>
        <?php $this->beginBody() ?>
            <? if ($campaign->enableThankYouPage): ?>
                <?= $campaign->thankYouPageText ?>
            <? else: ?>
            thank you page.
            <? endif ?>
        <?php $this->endBody() ?>
	</body>

</html>
<?php $this->endPage() ?>