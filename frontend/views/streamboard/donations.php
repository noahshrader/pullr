<!-- Accordion slide one (Donations) -->
<div id="donations" class="tab-pane active" data-ng-controller="DonationsController">
    <div class="text-center">
        <button class="btn btn-primary" data-ng-click="clearDonations()">Clear list</button>
        <button class="btn btn-primary" data-ng-click="addDonation()" >Add donation</button>
    </div>
    <div class="donations_list">
        <div data-ng-repeat="donation in donations | selectedCampaigns:this | limitTo: 20" class="donation">
            <h3 class="donation_name">{{donation.nameFromForm}}</h3>
            <div><span class="donation_amount">${{donation.amount}}</span> {{donation.campaignName}}</div>
            <div class="donation_comments">{{donation.comments}}</div>
            <div class="donation_date">{{donation.paymentDate*1000 | date: 'MM/dd/yyyy hh:mma'}}</div>
        </div>
    </div>
    <?= $this->render('donations-panels') ?>
</div>