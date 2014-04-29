<?
use frontend\models\site\DeactivateAccount;
use kartik\widgets\ActiveForm;

$deactivateAccount = new DeactivateAccount();
?>
<div>
    <button class="btn btn-link" data-toggle="modal" data-target="#deactivateModal">Deactivate my account</button>
</div>


<!-- Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <? $form = ActiveForm::begin([
        'action'=> 'app/settings/deactivate' , 
        'fieldConfig' => ['autoPlaceholder'=>true],
        'enableAjaxValidation' => true,
      ]) ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Deactivate modal</h5>
      </div>
      <div class="modal-body">
          <?= $form->field($deactivateAccount, 'reason')->textarea();?>
          <?= $form->field($deactivateAccount, 'password')->passwordInput() ?>
          Keep in mind that you have 30 days before the account is completely removed. Contact us if there's anything we can do to change your mind.
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Deactivate my account</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Keep my account</button>
      </div>
      <? ActiveForm::end() ?>
    </div>
  </div>
</div>