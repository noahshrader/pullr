<?
    use yii\helpers\Html;
    use dosamigos\ckeditor\CKEditor;
?>
<div id="campaign-edit-form">
    <div class="module-inner">
        <h5><i class="icon icon-list"></i>Form</h5>
        <div class="row">
            <div class="col-md-6">
                <!-- Donor Comments -->
                <div class="form-group">
                    <?= $form->field($campaign, 'enableDonorComments', ['autoPlaceholder' => false])->label('Donor Comments <i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to disallow donor comments on your donation form."></i>')->checkbox([], false); ?>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Enable\disable donation form progress bar -->
                <div class="form-group">
                    <?= $form->field($campaign, 'enableDonationProgressBar', ['autoPlaceholder' => false])->label('Progress Bar <i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to hide the progress bar on your donation form."></i>')->checkbox([], false); ?>
                </div>
            </div>
        </div>
        <!-- Donate Button Text -->
        <div class="form-group">
            <?=$form->field($campaign, 'donationButtonText', ['autoPlaceholder' => false])->label('Donate Button Text <i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Have your donate buttons say something else."></i>')->input('text'); ?>
        </div>
        <!-- Form Visibility -->
        <div class="form-group field-campaign-formvisibility">
                <label>Form Visibility</label><i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Select 'Visible' if you want to show your form. Select 'Hidden' to hide your form."></i>
                
                <? $keyValues = [ true => 'Visible', false => 'Hidden']; ?>
                <?= Html::activeDropDownList($campaign, 'formVisibility', $keyValues, ['class' => 'select-block']) ?>
        </div>
    </div>
    <div class="module-inner">
        <h5><i class="icon icon-check-round-fill"></i>Thank You Page</h5>
        <div>
            <!-- Enable Thank You Page -->
            <div class="form-group">
                <?= $form->field($campaign, 'enableThankYouPage', ['autoPlaceholder' => false])->label('Thank You Page <i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Toggle to off to disallow users from redirecting to your thank you page after donating."></i>')->checkbox([], false); ?>
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
