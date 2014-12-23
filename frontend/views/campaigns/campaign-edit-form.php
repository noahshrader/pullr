<?
    use yii\helpers\Html;
    use artkost\trumbowyg\Widget;
?>
<div id="campaign-edit-form">
    <div class="module">
        <h5 class="module-title">Form</h5>
        <div class="module-inner">
            <div class="row">
                <div class="col-md-4">
                    <!-- Donor Comments -->
                    <div class="form-group">
                        <label>Donor Comments <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="top" title="Allows donor comments on your donation form."></i></label>
                        <?= $form->field($campaign, 'enableDonorComments', ['autoPlaceholder' => false])->checkbox([], false); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Enable\disable donation form progress bar -->
                    <div class="form-group">
                        <label>Progress Bar <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="top" title="Show a progress bar on your donation form."></i></label>
                        <?= $form->field($campaign, 'enableDonationProgressBar', ['autoPlaceholder' => false])->checkbox([], false); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Form Visibility -->
                    <div class="form-group">
                        <label>Form Visibility <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="top" title="Disable this if you want to prevent users from temporarily donating."></i></label>
                        <?= $form->field($campaign, 'formVisibility', ['autoPlaceholder' => false])->checkbox([], false); ?> 
                    </div>        
                </div>
            </div>
            <!-- Donate Button Text -->
            <div class="form-group float">
                <?=$form->field($campaign, 'donationButtonText', ['autoPlaceholder' => false])->label('Donate Button Text <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Change the text on your donate button"></i>')->input('text')->textInput(array('placeholder' => 'Donate')); ?>
            </div>
        </div>
    </div>
    <div class="module">
        <h5 class="module-title">Thank You Page</h5>
        <div class="module-inner center">

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
            </div>
        </div>
    </div>
</div>