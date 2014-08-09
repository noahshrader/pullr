<?
use yii\helpers\Url;

?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-primary" ng-click="clearDonations()">Clear donations list</button>
</div>
<div class="campaigns_list_panel">
    <div class="campaigns_list">
        <h3>Campaigns</h3>

        <div ng-repeat="campaign in campaigns">
            <label>
                <input type="checkbox" ng-model="campaign.streamboardSelected" ng-change="campaignChanged(campaign)">
                {{campaign.name}}
            </label>
            <br>
        </div>
    </div>
    <a class="close icon-cross"></a>
</div>
