<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div class="source-wrap" id='source-wrap-angular' ng-cloak style='display:none;'>    
    <div class="twitchStats module" ng-show="twitchUser">
        <div class="form-group">
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
                        &nbsp;{{donation.name}} (${{donation.amount}})<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span
                        ng-repeat="follower in donationsService.followers"
                        class="commaAfter" ng-show='streamService.showFollower'>
                    <span>
                        &nbsp;{{follower.display_name}} (followed)<!--removing space for .commaAfter
                    --></span>
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        class="commaAfter" ng-show='streamService.showSubscriber'>
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
                    <span ng-repeat="(groupAmount, groupDonations1) in donationsService.groupDonations"
                        class="commaAfter">
                        <span ng-repeat='donation in groupDonations1' class="commaAfter" > {{donation.name}}</span> (${{groupAmount}})
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        ng-show='streamService.showSubscriber'
                        class="commaAfter">
                    
                        &nbsp;{{subscriber.display_name}}<!--removing space for .commaAfter
                    -->
                        <span class="commaAfter" ng-show='$last'>(subscribed)</span>
                    </span> 

                    <span
                        ng-repeat="follower in donationsService.followers"
                        ng-show='streamService.showFollower' class="commaAfter">
                        
                            &nbsp;{{follower.display_name}}<!--removing space for .commaAfter
                        -->
                        <span class="commaAfter" ng-show='$last'>(followed)</span>
                    </span> 

                    
                </span>  
            </div>
        </div>
    </div>
    <div class="tags module">
        <div class="form-group">
            <h5>Tags</h5>
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