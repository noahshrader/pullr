<table id="donations-table" class="display donations-table extend" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="30%">Donor</th>
            <th width="20%">Amount</th>
            <th width="45%">Date</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>" class="donation-entry">
                <td>
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td class="raised">
                    <span>$<?= number_format($donation->amount) ?></span>
                </td>
                <td>
                   <?= (new DateTime())
                       ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
                       ->setTimestamp($donation->paymentDate)
                       ->format('M j, Y h:iA');
                   ?>
                </td>
                <td class="details-control">
                    <? if ($donation->comments): ?>
                        <i class="icon-arrow-down"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>