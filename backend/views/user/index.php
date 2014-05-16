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
            <th>
                Name
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
            <th>
                # of Campaigns
            </th>
            <th>
                Total Raised
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($users as $user): ?>
            <tr data-id='<?= $user->id?>'>
                <td><a href="user/view?id=<?=$user->id?>"><?= $user->name ?></a></td>
                <td><?= $user->email?> </td>
                <td><?= $user->getPlan() ?></td>
                <td><?= ($user->status == User::STATUS_ACTIVE) ? 'Active' : 'Inactive'?> </td>
                <td><?= date('M j Y', $user->created_at) ?> </td>
                <td><?= date('M j Y h:iA', $user->last_login) ?></td>
                <td><?= $user->getCampaigns()->count(); ?></td>
                <td><?= number_format($user->getCampaigns()->sum('amountRaised')) ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>