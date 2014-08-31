<?
use common\models\Campaign;
use yii\widgets\ActiveForm;

?>
<!-- BEGIN Donation Form -->

<section class="<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 'tip-jar' :'events-form' ?>">
    <h1 class="campaign-name"><?= $campaign->name ?></h1>
    <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
        <? 
           $charityName = '';
           if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
               $charityName = $campaign->customCharity;
           } 
           if ($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES && $campaign->charityId){
               $charityName = $campaign->charity->name;
           } 
        ?>
        <? if ($charityName):?>
            <h3 class="charity-name">for <span><?= $charityName ?></span>
            <? if ($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES): ?>
                <span class="approved icon-check-round-fill"></span></h3>
            <? endif ?>
        <? endif ?>
    <? endif; ?>
    <?php $form = ActiveForm::begin($campaign->firstGiving ? [] : ['options' => ['target' => '_blank']]) ?>
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
                                        <input type="text" name="other-amount" id="other-amount">
                                </div>
                        </div>
                </div>
            <? else: ?>
               <div class="field donation-amount">
				<input type="text" id="other-amount" value='1'>
				<span class="preamt">$</span>
				<span class="currency">USD</span>
                </div>
             </div>
            <? endif;?>
            <span class="hide"><?= $form->field($donation, 'amount',['labelOptions' => ['class' => 'hidden']])->hiddenInput() ?></span>
            <!-- Other Fields -->
            <div class="form-wrapper">
                <div class="field">
                    <input type="text" id="donation-name" name="Donation[nameFromForm]" value='<?= htmlspecialchars($donation->nameFromForm) ?>' placeholder="Name">
                </div>
                <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
                <div class="field">
                    <?= $form->field($donation, 'email', ['inputOptions' => ['placeholder' => 'Email']])->label(false); ?>
                </div>
                <? endif ?>
                <? if ($campaign->enableDonorComments): ?>
                <div class="field comments cf">
                    <textarea type="text" id='donation-comments' name="Donation[comments]" placeholder="Comments"><?=htmlspecialchars($donation->comments) ?></textarea>
                    <span class="counter"></span>
                </div>
                <? endif;?>
                <button type="submit" class="btn-primary btn donate">Donate $<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 1 : 5 ?></button>
                <p class="info">By submitting, I acknowledge that I have read the <a href="http://pullr.io/privacy" target="_blank">privacy policy</a> and <a href="http://pullr.io/terms-of-service" target="_blank">terms of service</a>.</p>
            </div>
    <?php ActiveForm::end(); ?>
</section>
<!-- END Donation Form -->