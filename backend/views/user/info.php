<?
use common\assets\CommonAsset;
use common\models\Campaign;

$campaigns = $user->getCampaigns(Campaign::STATUS_ACTIVE, false)->all();
$totalAmountRaised = $user->getCampaigns()->sum('amountRaised');
$this->registerJSFile('@web/js/user/info.js', CommonAsset::className());

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
<br />
<fieldset>
    <legend>Social networks</legend>
    <? $openId = \common\models\OpenIDToUser::findOne(['userId' => $user->id]) ?>
    <? if ($openId): ?>
        <?php /**@var $openId \common\models\OpenIDToUser*/ ?>
        <div><span class="userinfo-label"><?= $openId->serviceName ?>:</span> <span class="userinfo-value"> <?= $openId->url ?> </span></div>
    <? else:  ?>
        <div>none</div>
    <? endif; ?>
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
        <? foreach ($campaigns as $campaign): ?>
            <tr>
                <td><?= $campaign->name ?></td>
                <td><?= ($campaign->startDate ? date('M j Y', $campaign->startDate) : '') .'- '.($campaign->endDate ? date('M j Y', $campaign->endDate) : '') ?></td>
                <td><?= $campaign->charity ? $campaign->charity->name : '' ?></td>
                <td><?= number_format($campaign->goalAmount) ?></td>
                <td><?= number_format($campaign->amountRaised) ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>
</fieldset>