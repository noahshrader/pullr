<?php
use yii\helpers\Html; 
use common\models\User;
use common\assets\DataTableAsset;

DataTableAsset::register($this);
$this->registerJSFile('@web/js/event/index.js', DataTableAsset::className());

$this->title = 'Events';
?>
<h1><?= Html::encode($this->title) ?></h1>

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
        </tr>
    </thead>
    <tbody>
        <? foreach ($events as $event): ?>
            <tr data-id='<?= $event->id?>'>
                <td><?= $event->name ?></td>
                <td><?= $event->charity->name?> </td>
                <td><?= date('M j Y', $event->startDate) ?></td>
                <td><?= date('M j Y', $event->endDate)?> </td>
                <td><?= $event->amountRaised ?> </td>
                <td><?= $event->goalAmount ?></td>
                <td><?= $event->numberOfDonations ?></td>
                <td><?= $event->numberOfUniqueDonors ?></td>
                <td><?= $event->user->name ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>