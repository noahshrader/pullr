<?php
use yii\helpers\Html; 
use common\models\User;
use common\assets\DataTableAsset;

DataTableAsset::register($this);
$this->registerJSFile('@web/js/user/index.js', DataTableAsset::className());

$this->title = 'Users';
?>
<h1><?= Html::encode($this->title) ?></h1>

<table id="users-management-table"  class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th class="sorting" role="columnheader">
                Full Name
            </th>
            <th>
                Email Address
            </th>
            <th>
                Plan 
            </th>
            <th>
                Status
            </th>
            <th>
                Registered Date
            </th>
            <th>
                Last login
            </th>
        </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all">
        <? foreach ($users as $user): ?>
            <tr class='event' data-id='<?= $user->id?>'>
                <td><?= $user->name ?></td>
                <td><?= $user->email?> </td>
                <td></td>
                <td><?= ($user->status == User::STATUS_ACTIVE) ? 'Active' : 'Inactive'?> </td>
                <td><?= date('M j Y', $user->created_at) ?> </td>
                <td></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>