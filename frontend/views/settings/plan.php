<?
    use yii\widgets\ActiveForm;
?>
<div class="plan-show-<?= $user->plan ?>">
    <div class='plan-pro-advertisement'>
        <div style='min-height: 500px'>
        Some advertising text here - you can use pro-plan with more features
        </div>
        <div class='text-center'>
            <button class='btn btn-primary' onclick="$('.plan-show-base').toggleClass('plan-show-base plan-show-payment')">Go pro</button>
        </div>
    </div>

    <div class='payment'>
        <? $form = ActiveForm::begin(['options' => ['method' => 'POST']]) ?>
            <h2>Go Pro</h2>
            <div style='min-height: 100px'>
                <select name="subscription">
                    <option label="Yearly recurring $(<?= \Yii::$app->params['yearSubscription'] ?>/yr)"><?= \Yii::$app->params['yearSubscription'] ?></option>
                    <option label="Monthly recurring $(<?= \Yii::$app->params['monthSubscription']?>/mo)"><?= \Yii::$app->params['monthSubscription'] ?></option>
                </select>
            </div>
            <button class='btn btn-primary'>Complete purchase</button>
        <? ActiveForm::end() ?>
    </div>

    <div class='plan-pro text-center'>
        <h2><i class='glyphicon glyphicon-ok'></i></h2>
        You are currently on the Pullr Pro account.
    </div>
</div>