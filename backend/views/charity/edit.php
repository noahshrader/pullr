<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

use common\models\Charity; 
/**
 * @var yii\web\View $this
 */
$this->title = ($charity->id == 0) ? 'New charity' : 'Edit charity ' . $charity->name;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data', 'method' => 'POST']]); ?>

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
                
<?= $form->field($charity, 'name') ?>
<?= $form->field($charity, 'status')->dropDownList(array_combine(Charity::$STATUSES, Charity::$STATUSES));?>
<?= $form->field($charity, 'type')->dropDownList(array_combine(Charity::$TYPES, Charity::$TYPES)); ?>
<?= $form->field($charity, 'paypal') ?>
<?= $form->field($charity, 'url') ?>
<?= $form->field($charity, 'contact') ?>
<?= $form->field($charity, 'contactEmail') ?>
<?= $form->field($charity, 'contactPhone') ?>
<?= $form->field($charity, 'description')->textarea() ?>
<script type="text/javascript">
    function changeCharityStatus(el, newStatus){
        if (confirm('Are you sure to change status to '+ newStatus+ '?')){
            $('#charity-status').val(newStatus);
            $(el).parents('form').submit();
        } else {
            return false;
        }
    }
</script>
<div class="form-row">
        <button type="submit" class="btn btn-primary">Submit</button>
        <? if (!$charity->isNewRecord): ?>
        <? if ($charity->status != Charity::STATUS_ACTIVE): ?>
            <a class="btn btn-success" onclick="changeCharityStatus(this, '<?= Charity::STATUS_ACTIVE ?>')">
                Activate
            </a>
        <? endif; ?>
        <? if ($charity->status != Charity::STATUS_PENDING): ?>
            <a class="btn btn-warning" onclick="changeCharityStatus(this, '<?= Charity::STATUS_PENDING ?>')">
                Pending
            </a>
        <? endif; ?>
        <? if ($charity->status != Charity::STATUS_DELETED): ?>
            <a class="btn btn-danger" onclick="changeCharityStatus(this, '<?= Charity::STATUS_DELETED ?>')">
                Delete
            </a>
        <? endif; ?>
        <? endif; ?>
        <a href="charity" class="btn btn-link" >Back</a>
    </div>
<?php ActiveForm::end(); ?>