<?
    use yii\helpers\Html;
    use dosamigos\ckeditor\CKEditor;
?>
<div id="campaign-edit-form">
    <div class="module-inner">
        <h5><i class="icon mdi-action-toc"></i>Form</h5>
        <div class="row">
            <div class="col-md-6">
                <!-- Donor Comments -->
                <div class="form-group">
                    <label>Donor Comments <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to disallow donor comments on your donation form."></i></label>
                    <?= $form->field($campaign, 'enableDonorComments', ['autoPlaceholder' => false])->checkbox([], false); ?>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Enable\disable donation form progress bar -->
                <div class="form-group">
                    <label>Progress Bar <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to hide the progress bar on your donation form."></i></label>
                    <?= $form->field($campaign, 'enableDonationProgressBar', ['autoPlaceholder' => false])->checkbox([], false); ?>
                </div>
            </div>
        </div>
        <!-- Donate Button Text -->
        <div class="form-group float">
            <?=$form->field($campaign, 'donationButtonText', ['autoPlaceholder' => false])->label('Donate Button Text')->input('text')->textInput(array('placeholder' => 'What text do you want on your donate button?')); ?>
        </div>
        <!-- Form Visibility -->
        <div class="form-group field-campaign-formvisibility">
                <label>Form Visibility</label><i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Select 'Visible' if you want to show your form. Select 'Hidden' to hide your form."></i>
                
                <? $keyValues = [ true => 'Visible', false => 'Hidden']; ?>
                <?= Html::activeDropDownList($campaign, 'formVisibility', $keyValues, ['class' => 'select-block']) ?>
        </div>
    </div>
    <div class="module-inner">
        <h5><i class="icon mdi-action-done-all"></i>Thank You Page</h5>
        <!-- Enable Thank You Page -->
        <div class="form-group">
            <label>Thank You Page <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to disallow users from redirecting to your thank you page after donating."></i></label>
            <?= $form->field($campaign, 'enableThankYouPage', ['autoPlaceholder' => false])->checkbox([], false); ?>
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
