<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Notification;
use kartik\widgets\FileInput;

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
    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options'=> [
        'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
            <? if (!$user->openIDToUser):?>
            <div class="form-group user-images <?= $user->hasErrors('images') ? 'has-error' : '' ?>">
                <label class="control-label">Upload avatar</label> 
                <? $params = ['multiple' => false, 'accept' => 'image/*'];
                    echo FileInput::widget([
                        'name' => 'images[]',
                        'options' => $params,
                         'showUpload' => true,
                        'uploadOptions' => ['label' => false],
                        'buttonOptions' => ['label' => false],
                        'showRemove' => false,
                    ]); ?>
                 <? if ($user->hasErrors('images')): ?>
                    <?= Html::error($user, 'images', ['class' => 'help-block']); ?>
                <? endif; ?>
            <? endif ?>
            </div>
            <?= $form->field($user, 'name') ?>
            <?= $form->field($user, 'email')->input('text', ['disabled' => '']) ?>
            <? $timezones = timezone_identifiers_list();
               $keyValues = array_combine($timezones, $timezones);
                       ?>
            <?= $form->field($user, 'timezone')->dropDownList($keyValues); ?>
            <fieldset>
                <legend>Notifications</legend>

                <?= $form->field($notification, Notification::$NOTIFY_NEVER )->checkbox(); ?>

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
                <?= $form->field($changePasswordForm, 'oldPassword') ?>
                <?= $form->field($changePasswordForm, 'newPassword') ?>
                <?= $form->field($changePasswordForm, 'confirmPassword') ?>
            </fieldset>
            <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>
    <?php ActiveForm::end(); ?>
            </div>
<!-- table-cell div -->
<div class='col-xs-2'>
    <div class='frontend-right-widget'>
            <?= $this->render('settings-plan', [
                'user' => $user
            ]); ?>
    </div>
</div>
    </div>
</div>
</div>
