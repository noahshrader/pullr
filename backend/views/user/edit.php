<?php
use yii\helpers\Html; 
use common\models\User;
use kartik\widgets\ActiveForm;
use common\models\Plan;

$this->title = 'Edit user ' . $user->name;
?>
<div>
    <? $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]) ?>
        <?= $form->field($user, 'login'); ?>
        <?= $form->field($user, 'name'); ?>
        <?= $form->field($user, 'email'); ?>
        <?= $form->field($user, 'role')->dropDownList(array_combine(User::$ROLES, User::$ROLES)) ?>
        <?= $form->field($user, 'status')->dropDownList(array_combine(User::$STATUSES, User::$STATUSES)) ?>
        <?= $form->field($user, 'plan')->dropDownList(array_combine(Plan::$PLANS, Plan::$PLANS)) ?>
        <?= $form->field($user, 'password')->passwordInput(['autocomplete' => 'off']) ?>
        <?= $form->field($user, 'confirmPassword')->passwordInput(); ?>

    <div class="text-left">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
    <? ActiveForm::end() ?>
</div>