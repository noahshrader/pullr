<?
    use yii\widgets\ActiveForm;
?>

<!-- Modal -->
<div class="modal fade" id="goproModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/settings/prostepone',
                'options'=>['target'=>'_blank']
            ]) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">Go Pro</h5>
            </div>
            <div class="modal-body">
                <select name="subscription" class="form-control select-block">
                    <option value="<?= \Yii::$app->params['yearSubscription'] ?>">Yearly recurring $(<?= \Yii::$app->params['yearSubscription'] ?>/yr)</option>
                    <option value="<?= \Yii::$app->params['monthSubscription'] ?>">Monthly recurring $(<?= \Yii::$app->params['monthSubscription']?>/mo)</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default">Pay with PayPal</button>
                <button type="submit" class="btn btn-default" data-dismiss="modal">Don't go Pro</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>