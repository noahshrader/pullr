<?php
use yii\helpers\Html; 
use common\models\User;

$this->title = 'Users';
?>

<div class="admin-content-wrap">
    <a href="/user" class="btn btn-primary">All users</a>
    <a href="/user/online" class="btn btn-primary">Online users</a>

    <?if(count($users)):?>
    <table id="users-management-table"  class="table dataTable">
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
                <th></th>
            </tr>
        </thead>
        <tbody>
            <? $time = time();?>
            <? foreach ($users as $user): ?>
                <tr data-id='<?= $user->id?>'>
                    <td>
                        <a href="user/view?id=<?=$user->id?>"><?= $user->name ?></a>
                    </td>
                    <td><?= $user->email?> </td>
                    <td><?= $user->getPlan() ?></td>
                    <td><?= ($user->status == User::STATUS_ACTIVE) ? 'Active' : 'Inactive'?> </td>
                    <td><?= date('M j Y', $user->created_at) ?> </td>
                    <td><?= date('M j Y h:iA', $user->last_login) ?></td>
                    <td><?= $user->getCampaigns()->count(); ?></td>
                    <td><?= number_format($user->getCampaigns()->sum('amountRaised')) ?></td>
                    <td>
                        <? if($time - $user->updated_at <= \Yii::$app->params['secDiffOnline']):?>
                            <span class="label label-success">Online</span>
                        <? else:?>
                            <span class="label label-danger">Offline</span>
                        <?endif;?>
                    </td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
<?else:?>
    <h3>No users</h3>
<?endif;?>
</div>