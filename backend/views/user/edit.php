<?php
use yii\helpers\Html; 
use common\models\User;
use kartik\widgets\ActiveForm;

$this->title = 'Edit user ' . $user->name;
?>
<h1><?= Html::encode($this->title) ?></h1>

<div>
    <? $form = ActiveForm::begin() ?>
        <?= $form->field($user, 'login'); ?>
        <?= $form->field($user, 'name'); ?>
        <?= $form->field($user, 'email'); ?>
        <?= $form->field($user, 'role')->dropDownList(array_combine(User::$ROLES, User::$ROLES)) ?>
        <?= $form->field($user, 'status')->dropDownList(array_combine(User::$STATUSES, User::$STATUSES)) ?>
        <?= $form->field($user, 'password')->passwordInput(['autocomplete' => 'off']) ?>
        <?= $form->field($user, 'confirmPassword')->passwordInput(); ?>

    <div class="text-left">
        <button type="submit" class="btn btn-primary">Update</button><a href="user" class="btn btn-link">Back</a>
    </div>
    <? ActiveForm::end() ?>
</div>