<?php
use yii\web\View;
use common\models\Donation;

/**@var $this View */
?>
<!-- Accordion slide one (Donations) -->
<!--<div id="donations-header" class="text-center">
    <button data-ng-click="addDonation()">Test donation</button>
</div>-->
<div class="donations-list pane paddingBottom">
    <div ng-repeat="donation in donationsService.userDonations | donationsFilterToSelectedCampaigns | limitTo: 40" class="donation"
         ng-class="{wasRead: donation.streamboard.wasRead}">
        <div class="donation-wrap">
            <h3 class="list-title">
                {{donation.displayName}}
                <a ng-hide="donation.nameFromForm == ''" ng-click="nameHiddenToggle(donation)" class="mdi-action-visibility toggleview"
                   ng-class="{nameHidden: donation.streamboard.nameHidden}"></a>
            </h3>
            <p class="list-total">${{donation.amount}}</p>
            <p class="donation-comments">{{donation.comments}}</p>
            <span class="list-info">{{campaignsService.selectedCampaignsNumber > 1 ? donation.campaignName : ''}}</span>
            <span class="list-info">{{donation.paymentDate*1000 | date: 'MM/dd/yyyy hh:mma'}}</span>
        </div>
        <a ng-click="markAsRead(donation)" class="markread"><i class="mdi-toggle-radio-button-off"></i></a>
    </div>
</div>
<div class="right-side-footer">
    <?= $this->render('donations-panels') ?>
</div>
