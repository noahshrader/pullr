<?
use yii\helpers\Url;

?>
<div class="campaigns_list_panel pane">
    <div class="settings-wrap">
        <div class="campaigns_list module">
            <h5>Campaigns</h5>
            <div class="checkbox" ng-repeat="campaign in campaignsService.campaigns">
                <label ng-class="{on:campaign.streamboardSelected}">
                    <input type="checkbox" ng-model="campaign.streamboardSelected" ng-change="campaignsService.campaignChanged(campaign)">
                    {{campaign.name}}
                </label>
            </div>
        </div>
        <div class-"module">
            <div class="text-center streamboard-settings-header">
                <button class="btn btn-secondary btn-sm" ng-click="clearButton()">Clear donations list</button>
            </div>
        </div>
    </div>
</div>
