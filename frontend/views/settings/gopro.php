<?
    use yii\widgets\ActiveForm;
?>

<section class="tip-jar">
    <div class="form-wrapper">
        <!-- Pullr Basic -->
        <div class="payment">
            <form method="post" action="/app/settings/prostepone" target="_blank">
            <h2>Go Pro</h2>
            <div>
                <select name="subscription" class="form-control select-block">
                    <option label="Yearly recurring $(<?= \Yii::$app->params['yearSubscription'] ?>/yr)"><?= \Yii::$app->params['yearSubscription'] ?></option>
                    <option label="Monthly recurring $(<?= \Yii::$app->params['monthSubscription']?>/mo)"><?= \Yii::$app->params['monthSubscription'] ?></option>
                </select>
            </div>
            </br>
            <button class="btn btn-primary">Complete purchase</button>
            </form>
        </div>
    </div>
</section>