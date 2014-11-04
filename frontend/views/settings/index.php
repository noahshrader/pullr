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

        <?php if (Yii::$app->session->hasFlash('pro_success')):?>
        <div class="alert alert-info alert-dismissible systemNotification module">
            You have successfully purchased Pro account!
        </div>
        <?endif;?>

        <?php if (Yii::$app->session->hasFlash('pro_failure')):?>
            <div class="alert alert-info alert-dismissible systemNotification module">
                We're unable to process your request. Please try again later.
            </div>
        <?endif;?>

        <?php if (Yii::$app->session->hasFlash('pro_deactivate')):?>
            <div class="alert alert-info alert-dismissible systemNotification module">
                You still have Pro account by the end of prepaid period.
            </div>
        <?endif;?>

		<section>
			<div class="module">
				<h5 class="module-title">General</h5>
				<div class="module-inner">
					<?= $form->field($user, 'name') ?>
					<?= $form->field($user, 'email')->input('text', ['disabled' => '']) ?>
					<?
					$timezones = timezone_identifiers_list();
					$keyValues = array_combine($timezones, $timezones);
					?>
					<?= $form->field($user, 'timezone')->dropDownList($keyValues, ['class' => 'select-block', 'data-size' => '10']); ?>
				</div>
			</div>
			<div class="dashboard-notifications module">
				<h5 class="module-title">Dashboard Notifications</h5>
				<div class="module-inner">
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_NEW_FOLLOWER)->checkbox(); ?>
					</div>
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_NEW_SUBSCRIBER)->checkbox(); ?>
					</div>
				</div>
			</div>
			<div class="email-notifications module">
				<h5 class="module-title">Email Notifications</h5>
				<div class="module-inner">
					<div>
						<div class="checkbox">
							<?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>
							<? $attributes = $notification->getNotificationsAttributes(); ?>
						</div>
					</div>
					<label>Email me when:</label>
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_DONATION_RECEIVED)->checkbox(); ?>
					</div>
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_NEW_FEATURE_ADDED)->checkbox(); ?>
					</div>
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_NEW_THEME_AVAILABLE)->checkbox(); ?>
					</div>
					<div class="checkbox">
						<?= $form->field($notification, Notification::$NOTIFY_SYSTEM_UPDATE)->checkbox(); ?>
					</div>
				</div>	
			</div>
		</section>
		<section class="module">
			<div class="module-inner">
            	<a class="account-deactivate" data-toggle="modal" data-target="#deactivateModal">Deactivate my account</a>
            </div>
		</section>
		<div class="form-group text-center">
			<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
</section> <!-- END main settings -->
<?php ActiveForm::end(); ?>

<?= $this->render('deactivate', ['user' => $user]); ?>
<?= $this->render('deactivatepro'); ?>
<?= $this->render('gopro'); ?>

<div id="sidebar" class="plans pane"> <!-- BEGIN plans sidebar -->
	<?=
	$this->render('plan', [
	'user' => $user
	]);
	?>
</div> <!-- END plans sidebar -->