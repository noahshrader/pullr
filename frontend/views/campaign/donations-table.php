<table id="donations-table" class="display donations-table extend" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Date & Time</th>
            <th>Donor Name</th>
            <th>Amount</th>
            <th>Comments</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>">
                <td>
                   <?= date('M j, Y', $donation->paymentDate) ?>
                </td>
                <td>
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td>
                    $<?= number_format($donation->amount) ?>
                </td>
                <td>
                    <? if ($donation->comments): ?>
                    Yes
                    <? else: ?>
                        No
                    <? endif ?>
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