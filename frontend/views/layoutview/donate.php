<?
use common\models\Campaign;
?>
<!-- BEGIN Donation Form -->
<section class="events-form">
        <h1 class="campaign-name"><?= $campaign->name ?></h1>
        <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR): ?>
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
                    <span class="approved icon-checkmark"></span></h3>
                <? endif ?>
            <? endif ?>
        <? endif; ?>
        <form>
                <!-- Amount Selections -->
                <div id="donation-amount" class="cf">
                        <div class="choice fieldamount">
                                <label for="option1" class="active">$5<input type="radio" id="option1" name="donation-amount" class="toggle donation-amount" value="5" checked="checked"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option2" class="">$15<input type="radio" id="option2" name="donation-amount" class="toggle donation-amount" value="5" checked="checked"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option3" class="">$25<input type="radio" id="option3" name="donation-amount" class="toggle donation-amount" value="5" checked="checked"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option4" class="">$50<input type="radio" id="option4" name="donation-amount" class="toggle donation-amount" value="5" checked="checked"></label>
                        </div>
                        <div class="choice fieldamount">
                                <label for="option5" class="">$100<input type="radio" id="option5" name="donation-amount" class="toggle donation-amount" value="5" checked="checked"></label>
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
                <!-- Other Fields -->
                <div class="form-wrapper">
                        <div class="field">
                                <input type="text" placeholder="Name">
                        </div>
                        <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR): ?>
                        <div class="field">
                                <input type="email" placeholder="Email">
                        </div>
                        <? endif ?>
                        <div class="field comments cf">
                                <textarea type="text" placeholder="Comments"></textarea>
                                <span class="counter"></span>
                        </div>
                        <button class="btn donate">Donate $5</button>
                </div>
        </form>
</section>
<!-- END Donation Form -->

