<html>
	<head>
        <link href="common/web/css/frontend/donation/donation.css" rel="stylesheet">
	</head>
	<body>
		<? if ($campaign->enableThankYouPage): ?>
            <?= $campaign->thankYouPageText ?>
		<? else: ?>
		thank you page.
		<? endif ?>
	</body>
</html>