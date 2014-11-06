<?php
use common\components\PullrUtils;
?>

<table id="donations-table" class="display donations-table extend donations-table-for-donor" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="25%">Name</th>
            <th width="15%">Amount</th>
            <th width="25%">Campaign</th>
            <th width="30%">Date</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>" class="donation-entry">
                <td width="25%">
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td class="raised" width="15%">
                    <span>$<?= PullrUtils::formatNumber($donation->amount, 2) ?></span>
                </td>
                <td width="25%">
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
                        <i class="icon-arrow-down"></i>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>