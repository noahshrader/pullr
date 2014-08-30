<?
    use yii\helpers\Html;
    use dosamigos\ckeditor\CKEditor;
?>
<div id="campaign-edit-form">
    <h3>Donation Form Settings</h3>
    
    <!-- Form Visibility -->
    <div class="form-group field-campaign-formvisibility">
            <label>Form Visibility</label><i class="icon-help" data-toggle="tooltip" data-placement="bottom" title="Select form visibility."></i>
            
            <? $keyValues = [ true => 'Visible', false => 'Hidden']; ?>
            <?= Html::activeDropDownList($campaign, 'formVisibility', $keyValues, ['class' => 'select-block']) ?>
    </div>
    <!-- Donor Comments -->
    <div class="form-group">
        <?= $form->field($campaign, 'enableDonorComments')->checkbox([], false); ?>
    </div>
    <!-- Enable Thank You Page -->
    <div class="form-group">
        <?= $form->field($campaign, 'enableThankYouPage')->checkbox([], false); ?>
    </div>
    <!-- Custom Thank You HTML -->
    <div class="form-group">
    <?= $form->field($campaign, 'thankYouPageText')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'basic'
        ]) ?>
    </div>
</div>
