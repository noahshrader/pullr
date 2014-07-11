<!-- Accordion slide one (Donations) -->
<div id="donations" class="tab-pane active" data-ng-controller="DonationsController">
    <div class="text-center">
        <button class="btn btn-primary" data-ng-click="clearDonations()">Clear list</button>
        <button class="btn btn-primary" data-ng-click="addDonation()" >Add donation</button>
    </div>
    <div class="donations_list">
        <div data-ng-repeat="donation in donations | limitTo: 20" class="donation">
            <div class="donation_name">{{donation.name}}</div>
            <div class="donation_amount">${{donation.amount}}</div>
            <div class="donation_campaign">{{donation.campaign_name}}</div>
            <div class="donation_comments">{{donation.comments}}</div>
            <div class="donation_date">{{donation.date_formatted}}</div>
        </div>
    </div>
</div>