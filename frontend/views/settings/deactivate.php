<?
use frontend\models\site\DeactivateAccount;
use kartik\widgets\ActiveForm;

$deactivateAccount = new DeactivateAccount();
?>

<!-- Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog module">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h5 class="module-title">Deactivate My Account</h5>
		<div class="modal-content">
			<!-- Deactivation Form -->
			<? $form = ActiveForm::begin([
				'action'=> 'app/settings/deactivate' ,
				'fieldConfig' => ['autoPlaceholder'=>true],
				'enableAjaxValidation' => true,
			]) ?>
			<div class="modal-body">
				<?= $form->field($deactivateAccount, 'reason')->textarea();?>
				<?= $form->field($deactivateAccount, 'password')->passwordInput() ?>
			</div>
			<div class="modal-footer btn-container">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Deactivate</button>
			</div>
			<? ActiveForm::end() ?>
				<!-- /Deactivation Form -->
		</div>
	</div>
</div>