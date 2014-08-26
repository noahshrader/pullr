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

<section id="content" class="settings pane"> <!-- BEGIN main settings -->
	<div class="content-wrap">
		<span class="corner"></span>
		<section class="module">
			<h3>General</h3>
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
		</section>
		<section class="module">
			<fieldset>
				<h3>Email Notifications</h3>
				<?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>
				<? $attributes = $notification->getNotificationsAttributes(); ?>
				<h5>Email me </h5>
				<? foreach ($attributes as $attribute): ?>
				<?= $form->field($notification, $attribute)->checkbox(); ?>
				<? endforeach; ?>
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
		</section>
		<section class="module">
			<?=
			$this->render('deactivate', [
			'user' => $user
			]);
			?>
		</section>
		<div class="form-group text-center">
			<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</section> <!-- END main settings -->

<div id="sidebar" class="plans pane"> <!-- BEGIN plans sidebar -->
	<?=
	$this->render('plan', [
	'user' => $user
	]);
	?>
</div> <!-- END plans sidebar -->