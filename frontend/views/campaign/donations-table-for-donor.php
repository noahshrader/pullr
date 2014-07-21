<table id="donations-table" class="display donations-table extend donations-table-for-donor" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Date & Time</th>
            <th>Donor Name</th>
            <th>Amount</th>
            <th>Campaign</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>">
                <td class="details-control">
                    <? if ($donation->comments): ?>
                        <i class="icon-add2"></i>
                    <? endif ?>
                </td>
                <td>
                   <?= date('M j, Y h:mA', $donation->paymentDate) ?>
                </td>
                <td>
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td>
                    $<?= number_format($donation->amount) ?>
                </td>
                <td>
                     <?= $donation->campaign->name ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>