<?php
use yii\web\View;
use common\models\Donation;

/**@var $this View */
?>
<!-- Accordion slide one (Donations) -->
<!--<div id="donations-header" class="text-center">
    <button data-ng-click="addDonation()">Test donation</button>
</div>-->
<div class="donations-list pane">
    <div ng-repeat="donation in donationsService.donations | donationsFilterToSelectedCampaigns | limitTo: 20" class="donation"
         ng-class="{wasRead: donation.streamboard.wasRead}">
        <div class="donation-wrap">
            <h3 class="list-title">
                {{donation.displayName}}
                <a ng-hide="donation.nameFromForm == ''" ng-click="nameHiddenToggle(donation)" class="icon-eye toggleview"
                   ng-class="{nameHidden: donation.streamboard.nameHidden}"></a>
            </h3>
            <p class="list-total">${{number_format(donation.amount)}}</p>
            <p class="donation-comments">{{donation.comments}}</p>
            <span class="list-info">{{campaignsService.selectedCampaignsNumber > 1 ? donation.campaignName : ''}}</span>
            <span class="list-info">{{donation.paymentDate*1000 | date: 'MM/dd/yyyy hh:mma'}}</span>
        </div>
        <a ng-click="markAsRead(donation)" class="markread"><i class="icon-radio-empty"></i></a>
    </div>
</div>
<div class="right-side-footer">
    <?= $this->render('donations-panels') ?>
</div>
