<?

use kartik\widgets\ActiveForm;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<?
$form = ActiveForm::begin(['id' => 'ajaxLayoutTeamEdit',
            'action' => 'app/campaign/layoutteamedit?save=true&id=' . $layoutTeam->id,
            'fieldConfig' => ['autoPlaceholder' => true],
            'enableAjaxValidation' => true,
            'beforeSubmit' => new \yii\web\JsExpression('function(form) {
                form.ajaxSubmit(function(){
                    $("#modal-social-link").modal("hide");
                    updateLayoutTeams();
                });
                return false;
            }')])
?>
<div class="modal-body">
    <?= $form->field($layoutTeam, 'youtube'); ?>
    <?= $form->field($layoutTeam, 'twitter'); ?>
    <?= $form->field($layoutTeam, 'facebook'); ?>
</div>
<div class="modal-footer">
    <button type="submit"  class="btn btn-primary">Save</button>
</div>
<? ActiveForm::end(); ?>
      