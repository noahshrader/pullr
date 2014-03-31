<?php
use yii\helpers\Html; 
use common\assets\DataTableAsset;
use yii\widgets\ActiveForm;
use common\models\Event;

DataTableAsset::register($this);
$this->registerJSFile('@web/js/event/index.js', DataTableAsset::className());

$this->title = 'Events';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin(); ?>
<label>
    Status:
    <select class="form-control" name='status' onchange="$(this).parents('form').submit()">
        <?php foreach (Event::$STATUSES as $_status): ?>
            <option <?php if (isset($status) && $status == $_status) echo 'selected' ?>><?= $_status ?></option>
        <?php endforeach; ?>
    </select>
</label>
<table id="events-management-table"  class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>
                Event name
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
        <? foreach ($events as $event): ?>
            <tr data-id='<?= $event->id?>'>
                <td><?= $event->name ?></td>
                <td><?= $event->charity->name?> </td>
                <td><?= date('M j Y', $event->startDate) ?></td>
                <td><?= date('M j Y', $event->endDate)?> </td>
                <td><?= number_format($event->amountRaised) ?> </td>
                <td><?= number_format($event->goalAmount) ?></td>
                <td><?= $event->numberOfDonations ?></td>
                <td><?= $event->numberOfUniqueDonors ?></td>
                <td><?= $event->user->name ?></td>
                <? switch ($event->status){
                        case (Event::STATUS_ACTIVE):
                            $class = 'success';
                            break;
                        case (Event::STATUS_INACTIVE):
                            $class = 'danger';
                            break;
                        default:
                            break;
                   } 
                ?>
                <td><span class="label label-<?= $class ?>"><?= $event->status ?></span></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
<? ActiveForm::end() ?>