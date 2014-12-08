<?
    use yii\helpers\Html;
    use artkost\trumbowyg\Widget;
?>
<div id="campaign-edit-form">
    <div class="module-inner">
        <h5><i class="icon mdi-action-toc"></i>Form</h5>
        <div class="row">
            <div class="col-md-4">
                <!-- Donor Comments -->
                <div class="form-group">
                    <label>Donor Comments <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle OFF to disallow donor comments on your donation form."></i></label>
                    <?= $form->field($campaign, 'enableDonorComments', ['autoPlaceholder' => false])->checkbox([], false); ?>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Enable\disable donation form progress bar -->
                <div class="form-group">
                    <label>Progress Bar <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle OFF to hide the progress bar on your donation form."></i></label>
                    <?= $form->field($campaign, 'enableDonationProgressBar', ['autoPlaceholder' => false])->checkbox([], false); ?>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Form Visibility -->
                <div class="form-group">
                    <label>Form Visibility <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Select 'Visible' if you want to show your form. Select 'Hidden' to hide your form."></i></label>
                    <?= $form->field($campaign, 'formVisibility', ['autoPlaceholder' => false])->checkbox([], false); ?> 
                </div>        
            </div>
        </div>
        <!-- Donate Button Text -->
        <div class="form-group float">
            <?=$form->field($campaign, 'donationButtonText', ['autoPlaceholder' => false])->label('Donate Button Text')->input('text')->textInput(array('placeholder' => 'What text do you want on your donate button?')); ?>
        </div>
    </div>
    <div class="module-inner center">
        <h5><i class="icon mdi-action-done-all"></i>Thank You Page</h5>
        <!-- Enable Thank You Page -->
        <div class="form-group">
            <label>Custom Message <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Toggle ON to add your own custom message on your thank you page."></i></label>
            <?= $form->field($campaign, 'enableThankYouPage', ['autoPlaceholder' => false])->checkbox([], false); ?>
        </div>
        <!-- Custom Thank You HTML -->
        <div class="form-group thankyoutext text-left">
        <?=
            $form->field($campaign, 'thankYouPageText')->widget(Widget::className(), [
                'settings' => [
                    'fullscreenable' => false,
                    'resetCss' => true,
                    'btns' => ['viewHTML',
                        '|', 'formatting',
                        '|', ['bold', 'italic', 'underline'],
                        '|', 'link',
                        '|', 'insertImage',
                        '|', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        '|', ['unorderedList', 'orderedList'],
                        '|', 'insertHorizontalRule']
                ]]);
        ?>
        <?php
//            echo
//            $form->field($campaign, 'thankYouPageText')->label(false)->widget(CKEditor::className(), [
//                'options' => ['rows' => 6],
//                'preset' => 'basic'
//            ])
        ?>
        </div>
    </div>
</div>
