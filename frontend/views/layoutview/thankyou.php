<html>
    <head>
        
    </head>
    <body>
        
        <? if ($campaign->enableThankYouPage): ?>
            <?= $campaign->thankYouPageText ?>
        <? else: ?>
            thank you page.
        <? endif ?>
    </body>
</html>