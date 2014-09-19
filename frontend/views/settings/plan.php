<?
use yii\widgets\ActiveForm;
use common\models\Plan;

?>


<div class="plan-show-<?= $user->plan ?> plan">

    <?php if($user->plan === Plan::PLAN_BASE):?>

    <!-- Pullr Basic -->
    <div class="plan-pro-advertisement">
        <div>
            <p>Some advertising text here - you can use pro-plan with more features</p>
        </div>
        <div class="account-action">
            <button class="btn btn-primary" data-target="#goproModal" data-toggle="modal">Go Pro</button>
        </div>
    </div>

    <?php else:?>

    <!-- Pullr Pro -->
    <div class="account-confirmation">
        <i class="icon-gamepad"></i>
        <h4>You are currently on Pullr Pro!</h4>
        <?
            $plan = Plan::findOne($user->id);
        ?>
    </div>
    <div class="account-action">
        <span>Paid until</span>
        <?= (new DateTime())
            ->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone()))
            ->setTimestamp($plan->expire)
            ->format('M j Y');
        ?>
        <a data-toggle="modal" data-target="#deactivateproModal">Downgrade my plan</a>
    </div>
    <?php endif;?>
</div>