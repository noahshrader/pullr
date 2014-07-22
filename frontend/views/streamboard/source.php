<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View*/
?>
<div ng-app="streamboardApp">
    <div ng-controller="SourceCtrl">
        <div>

        </div>
        <div class="source-row" ng-repeat="campaign in campaigns">
            <div class="name">{{campaign.name}}</div>
            <div>Amount Raised: <span class="amount accent">${{campaign.amountRaised}}</span></div>
            <div>Goal Amount: <span class="amount accent">${{campaign.goalAmount}}</span></div>
        </div>
    </div>
</div>
