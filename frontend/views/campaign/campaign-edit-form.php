<?
    use yii\helpers\Html;
    use dosamigos\ckeditor\CKEditor;
?>
<div id="campaign-edit-form">
    <div class="module">

        <h5>General</h5>
        
        <!-- Form Visibility -->
        <div class="form-group field-campaign-formvisibility">
                <label>Form Visibility</label><i class="icon icon-help" data-toggle="tooltip" data-placement="bottom" title="Select 'Visible' if you want to show your form. Select 'Hidden' to hide your form."></i>
                
                <? $keyValues = [ true => 'Visible', false => 'Hidden']; ?>
                <?= Html::activeDropDownList($campaign, 'formVisibility', $keyValues, ['class' => 'select-block']) ?>
        </div>
        <div class="row">
	        <div class="col-sm-6">
		        <!-- Donor Comments -->
		        <div class="form-group">
		            <?= $form->field($campaign, 'enableDonorComments')->checkbox([], false); ?>
		        </div>
	        </div>
	        <div class="col-sm-6">
		        <!-- Enable Thank You Page -->
		        <div class="form-group">
		            <?= $form->field($campaign, 'enableThankYouPage')->checkbox([], false); ?>
		        </div>
	        </div>
        </div>
        <!-- Custom Thank You HTML -->
        <div class="form-group">
        <?= $form->field($campaign, 'thankYouPageText')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'basic'
            ]) ?>
        </div>
    </div>
</div>
