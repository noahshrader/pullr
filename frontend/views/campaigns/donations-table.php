<table id="donations-table" class="display donations-table extend" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="30%">Donor</th>
            <th width="25%">Amount</th>
            <th width="35%">Date</th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($donations as $donation): ?>
            <tr data-email="<?= $donation->email ?>" data-comments="<?= $donation->comments ?>" class="donation-entry">
                <td width="30%">
                    <?= $donation->name ? $donation->name : 'Anonymous' ?>
                </td>
                <td class="raised" width="25%">
                    <span>$<?= \common\components\PullrUtils::formatNumber($donation->amount, 2) ?></span>
                </td>
                <td width="35%">
                   <?= (new DateTime())
                       ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
                       ->setTimestamp($donation->paymentDate)
                       ->format('M j, Y h:iA');
                   ?>
                </td>
                <td class="details-control" width="10%">
                    <? if ($donation->comments): ?>
                        <i class="mdi-navigation-expand-more"></i>
                    <? endif ?>
                    <? if ($donation->isManual): ?>
                        <a href="<?=\yii\helpers\Url::to(['campaigns/deletemanualdonation', 'donationId' => $donation->id]);?>"><i class="mdi-navigation-close" id="delete-manual-donation"></i></a>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<form action="/app/campaigns/importdonations" method="post" enctype="multipart/form-data">
    <input type="file" id="csv-upload" name="UploadCsv[file]" style="display:none" />
    <input type="hidden" name="UploadCsv[campaignId]" value="<?=$campaignId;?>"/>
</form>