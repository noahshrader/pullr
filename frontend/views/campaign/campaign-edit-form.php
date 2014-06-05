<?
    use yii\helpers\Html;
    use dosamigos\ckeditor\CKEditor;
?>
<div id="campaign-edit-form">
   <div class="form-group field-campaign-formvisibility">
            <label>Form Visibility:</label><i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select form visibility."></i>
            
            <? $keyValues = [ true => 'Visible', false => 'Hidden']; ?>
            <?= Html::activeDropDownList($campaign, 'formVisibility', $keyValues, ['class' => 'form-control select-block']) ?>
    </div>
    <?= $form->field($campaign, 'enableDonorComments')->checkbox([], false); ?>
    <?= $form->field($campaign, 'enableThankYouPage')->checkbox([], false); ?>
    <?= $form->field($campaign, 'thankYouPageText')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'basic'
        ]) ?>
</div>
