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
            <button class="btn btn-primary" onclick="$('.plan-show-Basic').toggleClass('plan-show-Basic plan-show-payment')">Go Pro</button>
        </div>
    </div>

    <!-- Pullr Basic -->
    <div class="payment">
        <? $form = ActiveForm::begin(['options' => ['method' => 'POST']]) ?>
        <h2>Go Pro</h2>
        <div>
            <select name="subscription" class="form-control select-block">
                <option label="Yearly recurring $(<?= \Yii::$app->params['yearSubscription'] ?>/yr)"><?= \Yii::$app->params['yearSubscription'] ?></option>
                <option label="Monthly recurring $(<?= \Yii::$app->params['monthSubscription']?>/mo)"><?= \Yii::$app->params['monthSubscription'] ?></option>
            </select>
        </div>
        <button class="btn btn-primary">Complete purchase</button>
        <? ActiveForm::end() ?>
    </div>

    <!-- Pullr Pro -->
    <div class="account-confirmation">
        <i class="icon-controller"></i>
        <h4>You are currently on Pullr Pro!</h4>
        <?
            $plan = Plan::findOne($user->id);
        ?>
    </div>
    <div class="account-action">
        <span>Paid until</span> <?= date('M j Y', $plan->expire) ?>
        <? if ($user->id < 10): ?>
            <a class="deactivate" href="app/settings/deactivatepro">Downgrade my plan</a>
        <? endif;?>
    </div>
</div>