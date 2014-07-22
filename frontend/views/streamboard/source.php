<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View*/
?>
<div ng-app="streamboardApp">
    <div ng-controller="SourceCtrl">
        <div>

        </div>
        <div id="campaign_{{campaign.id}}" class="source-row" ng-repeat="campaign in campaigns">
            <div class="name" id="campaignName_{{campaign.id}}">{{campaign.name}}</div>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_{{campaign.id}}">${{number_format(campaign.amountRaised)}}</span></div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_{{campaign.id}}">${{number_format(campaign.goalAmount)}}</span></div>
        </div>
    </div>
</div>
