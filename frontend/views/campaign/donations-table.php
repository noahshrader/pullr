<table id="donations-table" class="display donations-table extend" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="45%">Date &amp; Time</th>
            <th width="30%">Donor Name</th>
            <th width="20%">Amount</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>">
                <td>
                   <?= date('M j, Y h:mA', $donation->paymentDate) ?>
                </td>
                <td>
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td>
                    $<?= number_format($donation->amount) ?>
                </td>
                <td class="details-control">
                    <? if ($donation->email || $donation->comments): ?>
                        <i class="icon-arrowbottom"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>