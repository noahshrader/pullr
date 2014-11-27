<?
    use yii\widgets\ActiveForm;
?>

<!-- Modal -->
<div class="modal fade" id="goproModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog module">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="module-title">Go Pro!</h5>
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/settings/prostepone',
                'options'=>['target'=>'_blank']
            ]) ?>
            <div class="modal-body">
                <p>Get full access to all of Pullr's features. There are no commitments and you can cancel at any time.</p>
                <div class="form-group">
                    <label>Select a billing preference</label>
                    <select name="subscription" class="form-control select-block">
                        <option value="<?= \Yii::$app->params['yearSubscription'] ?>">Yearly recurring $(<?= \Yii::$app->params['yearSubscription'] ?>/yr)</option>
                        <option value="<?= \Yii::$app->params['monthSubscription'] ?>">Monthly recurring $(<?= \Yii::$app->params['monthSubscription']?>/mo)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer btn-container">
                <button type="submit" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Pay with PayPal</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>