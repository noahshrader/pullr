<?
use common\models\Campaign;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>

<!-- BEGIN Donation Form -->
<section class="<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 'tip-jar' :'events-form' ?>">
    <div class="donation-form-header">
        <h2 class="main-title" ng-cloak>{{campaign.name}}</h2>
        <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
            <? 
               $charityName = '';
               if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
                   $charityName = $campaign->customCharity;
               } 
               if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES && $campaign->charityId){
                   $charityName = $campaign->charity->name;
               } 
            ?>
            <? if ($charityName):?>
                <h3 class="charity-name">for <span><?= $charityName ?></span>
                <? if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES): ?>
                    <a class="approved">
                        <i class="icon mdi-toggle-check-box"></i>
                        <span class="approved-info">This is a validated organization.</span>
                    </a>
                </h3>
                <? endif ?>
            <? endif ?>
        <? endif; ?>
    </div>
    <?php $form = ActiveForm::begin($campaign->firstGiving ? [] : ['options' => ['target' => '_blank']]) ?>
        <div class="form-wrapper">
            <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
                <!-- Amount Selections -->
                <div id="donation-amount" class="cf">
                        <div class="choice fieldamount">
                                <label for="option1" class="active">$5<input type="radio" id="option1" name="donation-amount" class="toggle donation-amount" value="5"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option2" class="">$15<input type="radio" id="option2" name="donation-amount" class="toggle donation-amount" value="15"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option3" class="">$25<input type="radio" id="option3" name="donation-amount" class="toggle donation-amount" value="25"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option4" class="">$50<input type="radio" id="option4" name="donation-amount" class="toggle donation-amount" value="50"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option5" class="">$100<input type="radio" id="option5" name="donation-amount" class="toggle donation-amount" value="100" ></label>
                        </div>
                        <div class="fieldamount otheramount">
                                <label for="otheramount" class="other">Other<input type="radio" id="otheramount" name="donation-amount" class="toggle donation-amount other" value="other"></label>
                        </div>
                        <div id="other" style="display:none;">
                                <a class="closethis">&times;</a>
                                <span class="dollar-sign">$</span>
                                <div class="field">
                                    <?= MaskedInput::widget([
                                        'name' => 'other-amount',
                                        'value' => 1,
                                        'options' => [
                                            'id' => 'other-amount'
                                        ],
                                        'clientOptions' => [
                                            'alias' =>  'decimal',
                                            'groupSeparator' => ',',
                                            'autoGroup' => true,
                                            'autoUnmask' => true,
                                            'rightAlign' => false,
                                            'allowMinus' => false,
                                            'allowPlus' => false
                                        ],
                                    ]) ?>
                                </div>
                        </div>
                </div>
                <? else: ?>
                <div class="field form-group donation-amount">
                    <?= MaskedInput::widget([
                        'name' => 'other-amount',
                        'value' => 1,
                        'options' => [
                            'id' => 'other-amount'
                        ],
                        'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'rightAlign' => false,
                            'allowMinus' => false,
                            'allowPlus' => false
                        ],
                    ]) ?>
                    <span class="preamt">$</span>
                    <span class="currency">USD</span>
                </div>
                <? endif;?>
            <span class="hide"><?= $form->field($donation, 'amount',['labelOptions' => ['class' => 'hidden']])->hiddenInput() ?></span>
            <div class="form-group float">
                <input type="text" id="donation-name" name="Donation[nameFromForm]" value='<?= htmlspecialchars($donation->nameFromForm) ?>' placeholder="Name">
            </div>
            <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
            <div class="form-group float">
                <?= $form->field($donation, 'email', ['inputOptions' => ['placeholder' => 'Email']])->label(''); ?>
            </div>
            <? endif ?>
            <? if ($campaign->enableDonorComments): ?>
            <div class="form-group comments float">
                <textarea type="text" id='donation-comments' name="Donation[comments]" placeholder="Comments"><?=htmlspecialchars($donation->comments) ?></textarea>
                <span class="counter"></span>
            </div>
            <? endif;?>
            <button type="submit" class="btn-primary btn donate <? if($campaign->firstGiving):?>continue<? endif;?>" data-dtext="<?=htmlspecialchars(trim($campaign->donationButtonText), ENT_QUOTES, 'UTF-8');?>">
                Donate $<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 1 : 5 ?>
            </button>
            <p class="info">By submitting, I acknowledge that I have read the <a href="http://pullr.io/privacy" target="_blank">Privacy Policy</a> and <a href="http://pullr.io/terms-of-service" target="_blank">Terms of Service</a>.</p>
        </div>
    <?php ActiveForm::end(); ?>
</section>
<!-- END Donation Form -->