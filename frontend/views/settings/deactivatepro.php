<?
    use yii\widgets\ActiveForm;
    $deactPro = new \frontend\models\site\DeactivatePro();
?>

<!-- Modal -->
<div class="modal fade" id="deactivateproModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog module">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="module-title">Downgrade Your Plan</h5>
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/settings/deactivatepro'
            ]) ?>
            <div class="modal-body">
                <?= $form->field($deactPro, 'reasonId')->dropDownList($deactPro->getReasons());?>
            </div>
            <div class="modal-footer btn-container">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Don't Renew</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>