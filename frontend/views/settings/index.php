<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Notification;
use common\widgets\file\ImageInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $user
 */
$this->title = 'Account Settings';
?>
<div class="pullr-table">
    <div class='pullr-table-row row'>
        <div class="col-xs-10">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(['options' => [
                            'enctype' => 'multipart/form-data', 'method' => 'POST']])
            ?>
                <? if (!$user->openIDToUser): ?>
                <div class="form-group user-images <?= $user->hasErrors('images') ? 'has-error' : '' ?>">
                    <label class="control-label">Upload avatar</label> 
                    <?=ImageInput::widget();?>
                <? if ($user->hasErrors('images')): ?>
                    <?= Html::error($user, 'images', ['class' => 'help-block']); ?>
                <? endif; ?>
                </div>
            <? endif ?>
            <?= $form->field($user, 'name') ?>
            <?= $form->field($user, 'email')->input('text', ['disabled' => '']) ?>
<?
$timezones = timezone_identifiers_list();
$keyValues = array_combine($timezones, $timezones);
?>
                <?= $form->field($user, 'timezone')->dropDownList($keyValues); ?>
            <fieldset>
                <legend>Notifications</legend>

<?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>

<? $attributes = $notification->getNotificationsAttributes(); ?>
                <div class="row">
                    <div class="col-xs-2">
                        <span style="position: relative; top: 8px">
                            Email me 
                        </span>
                    </div>
                    <div class="col-xs-10">
<? foreach ($attributes as $attribute): ?>
    <?= $form->field($notification, $attribute)->checkbox(); ?>
                <? endforeach; ?>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Change Password</legend>
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
            <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>
<?php ActiveForm::end(); ?>
<?=
$this->render('deactivate', [
    'user' => $user
]);
?>
        </div>
        <!-- table-cell div -->
        <div class='col-xs-2'>
            <div class='frontend-right-widget'>
<?=
$this->render('plan', [
    'user' => $user
]);
?>
            </div>
        </div>
    </div>
</div>
</div>
