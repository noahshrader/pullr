<?
    use yii\widgets\ActiveForm;
    $deactPro = new \frontend\models\site\DeactivatePro();
?>

<!-- Modal -->
<div class="modal fade" id="deactivateproModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/settings/deactivatepro'
            ]) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="myModalLabel">Cancel Pro</h5>
            </div>
            <div class="modal-body">
                <?= $form->field($deactPro, 'reasonId')->dropDownList($deactPro->getReasons());?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Don't renew</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Stay subcribed</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>