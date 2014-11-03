<table id="donations-table" class="display donations-table extend" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="30%">Donor</th>
            <th width="20%">Amount</th>
            <th width="40%">Date</th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>" class="donation-entry">
                <td width="30%">
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td class="raised" width="20%">
                    <span>$<?= number_format($donation->amount, 2) ?></span>
                </td>
                <td width="40%">
                   <?= (new DateTime())
                       ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
                       ->setTimestamp($donation->paymentDate)
                       ->format('M j, Y h:iA');
                   ?>
                </td>
                <td class="details-control" width="10%">
                    <? if ($donation->comments): ?>
                        <i class="icon-arrow-down"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>