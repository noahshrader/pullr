<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\user\TwitchLogin;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */
$this->title = 'Login';
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>Please fill out the following fields to login:</p>

	<?= TwitchLogin::widget() ?>
                     <br />
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
				<?= $form->field($model, 'login') ?>
				<?= $form->field($model, 'password')->passwordInput() ?>
				<?= $form->field($model, 'rememberMe')->checkbox() ?>
				<div style="color:#999;margin:1em 0">
					If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
				</div>
				<div class="form-group">
					<?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
				</div>
			<?php ActiveForm::end(); ?>

			 <p>By connecting Twitch with your Pullr account, you agree to our <a href="/privacy"><strong>Privacy Policy</strong></a> and our <a href="/terms-of-service"><strong>Terms of service</strong></a></p>
</div>
