<!-- Accordion slide one (Donations) -->
<div id="donations" class="tab-pane active" data-ng-controller="DonationsController">
    <div class="text-center">
        <button class="btn btn-primary" data-ng-click="clearList()">Clear list</button>
    </div>
    <input type="button" value="Create donation" data-ng-click="addDonation()" >
    <div class="donations_list">
        <div data-ng-repeat="donation in donations" class="donation">
            <div class="donation_name">{{donation.name}}</div>
            <div class="donation_amount">${{donation.amount}}</div>
            <div class="donation_campaign">{{donation.campaign_name}}</div>
            <div class="donation_comments">{{donation.comments}}</div>
            <div class="donation_date">{{donation.date_formatted}}</div>
        </div>
    </div>
</div>