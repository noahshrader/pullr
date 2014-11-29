<?
use yii\widgets\ActiveForm;
use common\models\Plan;

?>

<div class="plan-show-<?= $user->plan ?> plan">

    <?php if($user->plan === Plan::PLAN_BASE):?>

    <!-- Pullr Basic -->
    <div class="plan-pro-advertisement plan-panel">
        <i class="mdib-gamepad4"></i>
        <h3>Go Pro!</h3>
        <ul>
            <li>Team Fundraising</li>
            <li>2 Region Streamboard</li>
            <li>Up to 8 Active Campaigns</li>
            <li>0.5% Transaction Fee</li>
            <li>and more!</li>
        </ul>
        <div class="account-action">
            <button class="btn btn-primary" data-target="#goproModal" data-toggle="modal">Go Pro</button>
        </div>
    </div>

    <?php else:?>

    <!-- Pullr Pro -->
    <div class="account-confirmation plan-panel">
        <i class="mdib-gamepad4"></i>
        <h3>You are currently on Pullr Pro!</h3>
        <?
            $plan = Plan::findOne($user->id);
        ?>
    </div>
    <div class="account-action">
        <span>Paid until</span>
        <?= (new DateTime())
            ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
            ->setTimestamp($plan->expire)
            ->format('M j, Y');
        ?>
        <a data-toggle="modal" data-target="#deactivateproModal">Downgrade my plan</a>
    </div>
    <?php endif;?>
</div>