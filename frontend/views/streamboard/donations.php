<?php
    use yii\web\View;
    use common\models\Donation;
    /**@var $this View*/
?>
<!-- Accordion slide one (Donations) -->
<div id="donations" class="tab-pane " data-ng-controller="DonationsCtrl">
    <div id="donations-header" class="text-center">
        <button class="btn btn-primary" data-ng-click="clearDonations()">Clear list</button>
        <button class="btn btn-primary" data-ng-click="addDonation()" >Add donation</button>
    </div>
    <div class="donations-list data-list">
        <div data-ng-repeat="donation in donations | selectedCampaigns:$root | limitTo: 20" class="donation" ng-class="{wasRead: donation.streamboard.wasRead}">
            <h3 class="donation-name">
                {{donation.displayName}}
                <a ng-hide="donation.nameFromForm == ''" ng-click="nameHiddenToggle(donation)" class="icon-view toggleview" ng-class="{nameHidden: donation.streamboard.nameHidden}"></a>
                <a ng-hide="donation.streamboard.wasRead" ng-click="markAsRead(donation)" class="icon-check2 markread"></a>
            </h3>
            <h4 class="donation-amount">${{number_format(donation.amount)}}</h4>
            <p class="donation-comments">{{donation.comments}}</p>
            <span>{{selectedCampaignsNumber > 1 ? donation.campaignName : ''}}</span>
            <span class="donation-date">{{donation.paymentDate*1000 | date: 'MM/dd/yyyy hh:mma'}}</span>
        </div>
    </div>
    <div id="donations-footer">
        <?= $this->render('donations-panels') ?>
    </div>
</div>