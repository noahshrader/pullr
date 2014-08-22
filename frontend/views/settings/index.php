<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Notification;

/**
* @var yii\web\View $this
* @var yii\widgets\ActiveForm $form
* @var common\models\User $user
*/
$this->title = 'Settings';
?>

<section id="content"> <!-- BEGIN main settings -->
	<span class="corner"></span>
	<div class="module">
		<?php $form = ActiveForm::begin(['options' => [
				'enctype' => 'multipart/form-data', 'method' => 'POST']])
		?>
		<?= $form->field($user, 'name') ?>
		<?= $form->field($user, 'email')->input('text', ['disabled' => '']) ?>
		<?
				$timezones = timezone_identifiers_list();
				$keyValues = array_combine($timezones, $timezones);
		?>
		<?= $form->field($user, 'timezone')->dropDownList($keyValues, ['class' => 'select-block', 'data-size' => '10']); ?>
	</div>
	<div class="module">
		<fieldset>
				<legend>Notifications</legend>
				<?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>
				<? $attributes = $notification->getNotificationsAttributes(); ?>
				<div>
					<h4>Email me </h4>
					<div>
							<? foreach ($attributes as $attribute): ?>
							<?= $form->field($notification, $attribute)->checkbox(); ?>
							<? endforeach; ?>
					</div>
				</div>
		</fieldset>
		<? if (!$user->openIDToUser): ?>
		<fieldset>
				<legend>Change Password (Only for sample accounts)</legend>
				<? if ($changePasswordForm->success): ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Password successfully changed
				</div>
				<? endif; ?>
				<?= $form->field($changePasswordForm, 'oldPassword') ?>
				<?= $form->field($changePasswordForm, 'newPassword')->passwordInput(['autocomplete' => 'off']) ?>
				<?= $form->field($changePasswordForm, 'confirmPassword')->passwordInput() ?>
		</fieldset>
		<? endif ?>
	</div>
	<div class="form-group">
			<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
	<?=
	$this->render('deactivate', [
	'user' => $user
	]);
	?>
</section> <!-- END main settings -->

<div id="sidebar" class="plans pane"> <!-- BEGIN plans sidebar -->
	<?=
	$this->render('plan', [
	'user' => $user
	]);
	?>
</div> <!-- END plans sidebar -->