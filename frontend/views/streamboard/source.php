<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View*/
?>
<div ng-app="streamboardApp">
    <div ng-controller="SourceCtrl">
        <div class="overall" ng-show="length(campaigns) > 1">
            <div class="name">Overall</div>
            <div>Total Amount Raised: <span id="total_amount_raised" class="amount accent">${{number_format(stats.total_amountRaised)}}</span></div>
            <div>Total Goal Amount: <span id="total_goal_amount" class="amount accent">${{number_format(stats.total_goalAmount)}}</span></div>
            <div>Total Donations: <span id="total_donations" class="amount accent">{{stats.number_of_donations}}</span></div>
            <div>Total Donors: <span id="total_donors" class="amount accent">{{stats.number_of_donors}}</span></div>
        </div>
        <div id="campaign_{{campaign.id}}" class="source-row" ng-repeat="campaign in campaigns">
            <div class="name" id="campaignName_{{campaign.id}}">{{campaign.name}}</div>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_{{campaign.id}}">${{number_format(campaign.amountRaised)}}</span></div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_{{campaign.id}}">${{number_format(campaign.goalAmount)}}</span></div>
            <div>Donations: <span class="amount accent" id="donations_{{campaign.id}}">{{number_format(campaign.numberOfDonations)}}</span></div>
            <div>Donors: <span class="amount accent" id="donors_{{campaign.id}}">{{number_format(campaign.numberOfUniqueDonors)}}</span></div>
        </div>
    </div>
</div>
