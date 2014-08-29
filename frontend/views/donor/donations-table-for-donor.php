<table id="donations-table" class="display donations-table extend donations-table-for-donor" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="25%">Display Name</th>
            <th width="10%">Amount</th>
            <th width="30%">Campaign</th>
            <th width="30%">Date</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>">
                <td width="25%">
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td class="raised" width="10%">
                    $<?= number_format($donation->amount) ?>
                </td>
                <td width="30%">
                     <?= $donation->campaign->name ?>
                </td>
                <td width="30%">
                   <?= (new DateTime())
                       ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
                       ->setTimestamp($donation->paymentDate)
                       ->format('M j, Y h:iA');
                   ?>
                </td>
                <td class="details-control" width="5%">
                    <? if ($donation->comments): ?>
                        <i class="icon-arrowbottom"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>