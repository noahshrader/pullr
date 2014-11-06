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
                <span ng-repeat="donor in donors" class="commaAfter">
                    <span class="nowrap">
                    <span>{{donor.name}}</span>
                   (<span class="amount accent">${{donor.amount}}</span>)<!-- removing space before :after{content:} rule
                --></span></span>
                <span ng-repeat="subscriber in subscribers" class="commaAfter" ng-show='streamService.showSubscriber'>
                    <span class="nowrap">
                    <span>{{subscriber.display_name}}</span>
                    <span>(Subscribed)</span><!-- removing space before :after(content:} rule
                --></span></span>
                <span ng-repeat="follower in followers" class="commaAfter" ng-show='streamService.showFollower'>
                    <span class="nowrap">
                    <span>{{follower.display_name}}</span>
                    <span>(Followed)</span><!-- removing space before :after(content:} rule
                --></span></span>
                <span ng-if="(donors.length == 0 && ( ! streamService.showSubscriber || subscribers.length == 0 ) && ( ! streamService.showFollower || followers.length == 0 )) ">
                    {{emptyActivityMessage}}
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