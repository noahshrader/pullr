<?
use common\assets\DataTableAsset;

$events = $user->events;
$totalAmountRaised = $user->getEvents()->sum('amountRaised');
$this->registerJSFile('@web/js/user/info.js', DataTableAsset::className());

?>
<fieldset>
    <legend>Profile</legend>
    <div><span class="userinfo-label">Login:</span> <span class="userinfo-value"> <?= $user->login ?> </span></div>
    <div><span class="userinfo-label">Fullname:</span> <span class="userinfo-value"> <?= $user->name ?> </span></div>
    <div><span class="userinfo-label">Email:</span> <span class="userinfo-value"> <?= $user->email ?> </span></div>
    <div><span class="userinfo-label">Plan:</span> <span class="userinfo-value"> <?= $user->getPlan() ?> </span></div>
    <div><span class="userinfo-label">Status:</span> <span class="userinfo-value"> <?= $user->status ?> </span></div>
    <div><span class="userinfo-label">Registered Date:</span> <span class="userinfo-value"> <?= date('M j Y', $user->created_at) ?></span></div>
    <div><span class="userinfo-label">Last Login:</span> <span class="userinfo-value"> <?= date('M j Y h:iA', $user->last_login) ?></span></div>
</fieldset>
<fieldset style="margin-top: 30px">
    <legend>Events/Donations</legend>
    <div><b>Total Amount Raised: <?= number_format($totalAmountRaised) ?> </b></div>
    <br>
    <table id="user-events"  class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>
                Event
            </th>
            <th>
                Dates
            </th>
            <th>
                Charity
            </th>
            <th>
                Goal
            </th>
            <th>
                Raised
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($events as $event): ?>
            <tr>
                <td><?= $event->name ?></td>
                <td><?= date('M j Y', $event->startDate).'- '.date('M j Y', $event->endDate) ?></td>
                <td><?= $event->charity->name ?></td>
                <td><?= number_format($event->goalAmount) ?></td>
                <td><?= number_format($event->amountRaised) ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
</fieldset>