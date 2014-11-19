<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div class="source-wrap" id='source-wrap-angular' ng-cloak style='display:none;'>    
    <div class="twitchStats module" ng-show="twitchUser">
        <div class="form-group">
            <h5>Twitch</h5>
            <div>Followers: <span id="followers_number" class="amount accent">{{followersNumber}}</span>
            </div>
            <div ng-show="Pullr.user.userFields.twitchPartner">Subscribers:
                <span id="subscriber_number" class="amount accent">{{subscribersNumber}}</span>
            </div>
        </div>
    </div>
    <div class="activityFeed module">
        <div class="form-group">
            <h5>Activity Feed</h5>
            <div class="sb-activity-feed">
                <span ng-if=' ! streamService.groupUser'>
                    <span
                        ng-repeat="donation in donationsService.donations | orderBy: amount | donationsFilterToSelectedCampaigns "
                        class="commaAfter">
                    <span>
                        &nbsp;{{donation.name}} (${{number_format(donation.amount)}})<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span
                        ng-repeat="follower in donationsService.followers"
                        class="commaAfter" ng-if='streamService.showFollower'>
                    <span>
                        &nbsp;{{follower.display_name}} (followed)<!--removing space for .commaAfter
                    --></span>
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        class="commaAfter" ng-if='streamService.showSubscriber'>
                    <span>
                        &nbsp;{{subscriber.display_name}} (subscribed)<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0 && ( ! streamService.showSubscriber || donationsService.subscribers.length == 0 ) && ( ! streamService.showFollower || donationsService.followers.length == 0 ) ">
                        <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                        <span ng-if="!region.widgetDonationFeed.noDonationMessage">No activity!</span>
                    </span> 
                </span> 
                <span ng-if='streamService.groupUser'>
                    <span ng-repeat="groupDonation in donationsService.groupDonations"
                        class="commaAfter">
                        <span ng-repeat='donation in groupDonation.items' class="commaAfter" > {{donation.name}}</span> (${{number_format(groupDonation.amount)}})
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        ng-if='streamService.showSubscriber'
                        class="commaAfter">
                    
                        &nbsp;{{subscriber.display_name}}<!--removing space for .commaAfter
                    -->
                        <span class="commaAfter" ng-show='$last && donationsService.subscribers.length > 0'>(subscribed)</span>
                    </span> 

                    <span
                        ng-repeat="follower in donationsService.followers"
                        ng-if='streamService.showFollower' class="commaAfter">
                        
                            &nbsp;{{follower.display_name}}<!--removing space for .commaAfter
                        -->
                        <span class="commaAfter" ng-show='$last && donationsService.followers.length > 0'>(followed)</span>
                    </span> 

                    
                </span>  
            </div>
        </div>
    </div>
    <div class="tags module">
        <div class="form-group">
            <h5>Tags</h5>
            <div>Last Follower: <span id='last_follower' ng-show="donationsService.followers.length > 0">{{donationsService.followers[0].display_name}}</span></div>
            <div>Last Subscriber: <span id='last_subscriber' ng-show="donationsService.subscribers.length > 0">{{donationsService.subscribers[0].display_name}}</span></div>
            <div>Last Donor: <span id='last_donor'>{{stats.last_donor.name}}</span></div>
            <div>Last Donor/Donation: <span id='last_donor_donation'>{{stats.last_donor.name}} (${{stats.last_donor.amount}})</span></div>
            <div>Largest Donation: <span id='largest_donation'>${{stats.top_donation.amount}} ({{stats.top_donation.displayName}})</span></div>
            <div>Top Donor: <span id='top_donor'>{{stats.top_donors[0].name}} (${{stats.top_donors[0].amount}})</span></div>
        </div>
    </div>
    <div class="tags module">
        <div class="form-group">
            <h5>New Tags</h5>
            <div>Top Donor: <span>{{stats.top_donors[0].name}}</span></div>
            <div>Top 3 Donors:<br>
                <span ng-repeat="donor in stats.top_donors">{{donor.name}} (${{number_format(donor.amount)}})<br></span>
            </div>
        </div>
    </div>
    <div class="overall module" ng-show="length(campaignsService.campaigns) > 1">
        <div class="form-group">
            <h5>Overall</h5>
            <div>Total Amount Raised: <span id="total_amount_raised" class="amount accent">${{stats.total_amountRaised}}</span>
            </div>
            <div>Total Goal Amount: <span id="total_goal_amount" class="amount accent">${{stats.total_goalAmount}}</span>
            </div>
            <div>Total Donations: <span id="total_donations" class="amount accent">{{stats.number_of_donations}}</span>
            </div>
            <div>Total Donors: <span id="total_donors" class="amount accent">{{stats.number_of_donors}}</span></div>
        </div>
    </div>
    <div id="campaign_{{campaign.id}}" class="source-row module campaign" ng-repeat="campaign in campaignsService.campaigns">
        <div class="form-group">
            <h5 id="campaignName_{{campaign.id}}">{{campaign.name}}</h5>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_{{campaign.id}}">${{(campaign.amountRaised)}}</span>
            </div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_{{campaign.id}}">${{(campaign.goalAmount)}}</span>
            </div>
            <div>Donations: <span class="amount accent" id="donations_{{campaign.id}}">{{(campaign.numberOfDonations)}}</span>
            </div>
            <div>Donors: <span class="amount accent" id="donors_{{campaign.id}}">{{(campaign.numberOfUniqueDonors)}}</span>
            </div>
        </div>
    </div>
    
</div>