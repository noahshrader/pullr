<?
use yii\widgets\ActiveForm;
use common\models\Plan;

?>


<div class="plan-show-<?= $user->plan ?> plan">
    <div class="plan-pro-advertisement">
        <div>
            <p>ome advertising text here - you can use pro-plan with more features</p>
        </div>
        <div class="account-action">
            <button class="btn btn-primary" onclick="//$('.plan-show-Basic').toggleClass('plan-show-Basic plan-show-payment')">Go Pro</button>
        </div>
    </div>



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
        <? if ($user->id < 10): ?>
            <a class="deactivate" href="app/settings/deactivatepro">Downgrade my plan</a>
        <? endif;?>
    </div>
</div>