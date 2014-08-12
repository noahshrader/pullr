<table id="donations-table" class="display donations-table extend donations-table-for-donor" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th widths="25%">Display Name</th>
            <th widths="10%">Amount</th>
            <th widths="30%">Campaign</th>
            <th widths="30%">Date &amp; Time</th>
            <th widths="5%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>">
                <td>
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td>
                    $<?= number_format($donation->amount) ?>
                </td>
                <td>
                     <?= $donation->campaign->name ?>
                </td>
                <td>
                   <?= date('M j, Y h:mA', $donation->paymentDate) ?>
                </td>
                <td class="details-control">
                    <? if ($donation->comments): ?>
                        <i class="icon-arrowbottom"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>