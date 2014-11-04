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
	<?= TwitchLogin::widget() ?>
        <? if (YII_ENV_DEV): ?>
            <div class="alert alert-info" role="alert">User/Pass available here only at pullr/dev configuration
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'login') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        <? endif ?>
        <a class="btn btn-default hitboxLogin"><i class="icon icon-hitbox"></i> Connect With Hitbox</a>
	<p class="small">By connecting with Pullr, you agree to our <a href="http://pullr.io/terms-of-service">Terms of Service</a>.</p>
</div>