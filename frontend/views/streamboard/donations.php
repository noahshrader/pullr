<?php
    use yii\web\View;
    use common\models\Donation;
    /**@var $this View*/
?>
<!-- Accordion slide one (Donations) -->
<div id="donations" class="tab-pane active" data-ng-controller="DonationsController">
    <div id="donations-header" class="text-center">
        <button class="btn btn-primary" data-ng-click="clearDonations()">Clear list</button>
        <button class="btn btn-primary" data-ng-click="addDonation()" >Add donation</button>
    </div>
    <div class="donations-list data-list">
        <div data-ng-repeat="donation in donations | selectedCampaigns:this | limitTo: 20" class="donation">
            <h3 class="donation-name">{{donation.nameFromForm ? donation.nameFromForm : '<?= Donation::ANONYMOUS_NAME ?>'}}</h3>
            <div><span class="donation-amount">${{donation.amount}}</span> {{donation.campaignName}}</div>
            <div class="donation-comments">{{donation.comments}}</div>
            <div class="donation-date">{{donation.paymentDate*1000 | date: 'MM/dd/yyyy hh:mma'}}</div>
        </div>
    </div>
    <div id="donations-footer">
        <?= $this->render('donations-panels') ?>
    </div>
</div>