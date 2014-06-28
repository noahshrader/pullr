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

<section class="account-settings">

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
            <?= $form->field($user, 'timezone')->dropDownList($keyValues, ['class' => 'select-block']); ?>
            
            <fieldset>
                <legend>Notifications</legend>
                <?= $form->field($notification, Notification::$NOTIFY_NEVER)->checkbox(); ?>
                <? $attributes = $notification->getNotificationsAttributes(); ?>
                <div class="row">

                    <h4>Email me </h4>

                    <div class="col-xs-6">
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

        
        <div id="side-panel" class='col-xs-3'>
            <div class='sidepanel basic'>
            <?=
            $this->render('plan', [
            'user' => $user
            ]);
            ?>
        </div>


</section>