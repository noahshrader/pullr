<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div ng-app="pullr.streamboard.sourceApp" class="view-source">
    <div ng-controller="SourceCtrl">
        <div class="twitchStats" ng-show="twitchUser">
            <div>Followers: <span id="followers_number" class="amount accent">{{twitchUser.followersNumber}}</span>
            </div>
            <div ng-show="Pullr.user.userFields.twitchPartner">Subscribers:
                <span id="subscriber_number" class="amount accent">{{twitchUser.subscribersNumber}}</span>
            </div>
        </div>
        <div class="activityFeed">
            <h4>Activity Feed</h4>
            <span ng-repeat="donor in donors" class="activityFeedElement">
                <span class="nowrap">
                <span>{{donor.name}}</span>
               (<span class="amount accent">${{number_format(donor.amount)}}</span>)<!-- removing space before :after{content:} rule
            --></span></span>
            <span ng-repeat="subscriber in subscribers" class="activityFeedElement">
                <span class="nowrap">
                <span>{{subscriber.display_name}}</span>
                <span>(Subscribed)</span><!-- removing space before :after(content:} rule
            --></span></span>
        </div>
        <div class="overall" ng-show="length(campaignsService.campaigns) > 1">
            <h4>Overall</h4>
            <div>Total Amount Raised: <span id="total_amount_raised" class="amount accent">${{number_format(stats.total_amountRaised)}}</span>
            </div>
            <div>Total Goal Amount: <span id="total_goal_amount" class="amount accent">${{number_format(stats.total_goalAmount)}}</span>
            </div>
            <div>Total Donations: <span id="total_donations" class="amount accent">{{stats.number_of_donations}}</span>
            </div>
            <div>Total Donors: <span id="total_donors" class="amount accent">{{stats.number_of_donors}}</span></div>
        </div>
        <div id="campaign_{{campaign.id}}" class="source-row" ng-repeat="campaign in campaignsService.campaigns">
            <h4 id="campaignName_{{campaign.id}}">{{campaign.name}}</h4>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_{{campaign.id}}">${{number_format(campaign.amountRaised)}}</span>
            </div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_{{campaign.id}}">${{number_format(campaign.goalAmount)}}</span>
            </div>
            <div>Donations: <span class="amount accent" id="donations_{{campaign.id}}">{{number_format(campaign.numberOfDonations)}}</span>
            </div>
            <div>Donors: <span class="amount accent" id="donors_{{campaign.id}}">{{number_format(campaign.numberOfUniqueDonors)}}</span>
            </div>
        </div>
    </div>
</div>