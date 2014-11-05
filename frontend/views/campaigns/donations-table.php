<?
use yii\widgets\ActiveForm;
?>

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
                    <span>$<?= number_format($donation->amount, 2) ?></span>
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

<? $manualDonation = new \frontend\models\site\ManualDonation(); ?>
<!-- Modal -->
<div class="modal fade" id="manualDonationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/campaigns/manualdonation'
            ]) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <?= $form->field($manualDonation, 'name')->input('text');?>
                <?= $form->field($manualDonation, 'email')->input('text');?>
                <?= $form->field($manualDonation, 'amount')->input('text');?>
                <?= $form->field($manualDonation, 'dateCreated')->label("Donation datetime")->input('datetime-local', ['value' => strftime('%Y-%m-%dT%H:%M', time())]); ?>
                <?= $form->field($manualDonation, 'comments')->textarea();?>
                <?= $form->field($manualDonation, 'campaignId')->hiddenInput(['value' => $campaignId])->label(false);?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Donation</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>