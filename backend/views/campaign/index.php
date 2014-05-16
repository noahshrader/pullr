<?php
use yii\helpers\Html; 
use common\assets\DataTableAsset;
use yii\widgets\ActiveForm;
use common\models\Campaign;

DataTableAsset::register($this);
$this->registerJSFile('@web/js/event/index.js', DataTableAsset::className());

$this->title = 'Campaigns';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin(); ?>
<label>
    Status:
    <select class="form-control" name='status' onchange="$(this).parents('form').submit()">
        <?php foreach (Campaign::$STATUSES as $_status): ?>
            <option <?php if (isset($status) && $status == $_status) echo 'selected' ?>><?= $_status ?></option>
        <?php endforeach; ?>
    </select>
</label>
<table id="events-management-table"  class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>
                Campaign name
            </th>
            <th>
                Charity
            </th>
            <th>
                Start Date
            </th>
            <th>
                End Date
            </th>
            <th>
                Amount Raised
            </th>
            <th>
                Goal Amount
            </th>
            <th>
                #of Donations
            </th>
            <th>
                #of Unique Donors
            </th>
            <th>
                User
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($campaigns as $campaign): ?>
            <tr data-id='<?= $campaign->id?>'>
                <td> <a href="campaign/edit?id=<?= $campaign->id?>"><?= $campaign->name ?></a></td>
                <td><?= $campaign->charity ? $campaign->charity->name : '' ?> </td>
                <td><?= $campaign->startDate ? date('M j Y', $campaign->startDate) : '' ?></td>
                <td><?= $campaign->endDate ? date('M j Y', $campaign->endDate) : ''?> </td>
                <td><?= number_format($campaign->amountRaised) ?> </td>
                <td><?= number_format($campaign->goalAmount) ?></td>
                <td><?= $campaign->numberOfDonations ?></td>
                <td><?= $campaign->numberOfUniqueDonors ?></td>
                <td><?= $campaign->user->name ?></td>
                <? switch ($campaign->status){
                        case (Campaign::STATUS_ACTIVE):
                            $class = 'success';
                            break;
                        case (Campaign::STATUS_DELETED):
                            $class = 'danger';
                            break;
                        default:
                            break;
                   } 
                ?>
                <td><span class="label label-<?= $class ?>"><?= $campaign->status ?></span></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
<? ActiveForm::end() ?>