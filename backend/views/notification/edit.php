<?php

use yii\widgets\ActiveForm;

use common\models\notifications\SystemNotification;
/**
 * @var yii\web\View $this
 */
$this->title = ($notice->id == 0) ? 'New notification' : 'Edit notification ' . $notice->id;
?>

<div class="admin-content-wrap">

    <?php $form = ActiveForm::begin(['id' => 'editNotice', 'options' => ['method' => 'POST']]); ?>

    <?= $form->field($notice, 'message'); ?>
    <?= $form->field($notice, 'status')->dropDownList(array_combine(SystemNotification::$STATUSES, SystemNotification::$STATUSES)); ?>
    <?= $form->field($notice, 'date')->input('datetime-local'); ?>

    <div class="form-row">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="notification" class="btn btn-link" >Back</a>
    </div>
    <?php ActiveForm::end(); ?>

</div>