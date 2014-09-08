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

<?php $form = ActiveForm::begin(['options' => [
    'enctype' => 'multipart/form-data', 'method' => 'POST']])
?>
<section id="content" class="settings pane"> <!-- BEGIN main settings -->
	<div class="content-wrap">
		<span class="corner"></span>
		<section class="module">
			<h4>General</h4>
			<?= $form->field($user, 'name') ?>
			<?= $form->field($user, 'email')->input('text', ['disabled' => '']) ?>
			<?
				$timezones = timezone_identifiers_list();
				$keyValues = array_combine($timezones, $timezones);
			?>
			<?= $form->field($user, 'timezone')->dropDownList($keyValues, ['class' => 'select-block', 'data-size' => '10']); ?>
		</section>
		<section class="module email-notifications">
			<fieldset>
				<h4>Email Notifications</h4>
				<div>
					<?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>
					<? $attributes = $notification->getNotificationsAttributes(); ?>
				</div>
				<h5>Email me when:</h5>
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
	</div>
</section> <!-- END main settings -->
<?php ActiveForm::end(); ?>

<div id="sidebar" class="plans pane"> <!-- BEGIN plans sidebar -->
	<?=
	$this->render('plan', [
	'user' => $user
	]);
	?>
</div> <!-- END plans sidebar -->