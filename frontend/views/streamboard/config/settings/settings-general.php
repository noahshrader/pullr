<?
use yii\helpers\Url;

?>
<h4 class="panel-head">Settings</h4>
<div class="campaigns_list_panel pane">
    <div class="settings-wrap">
        <div class="campaigns_list">
            <h3>Campaigns</h3>

            <div class="checkbox" ng-repeat="campaign in campaignsService.campaigns">
                <label ng-class="{on:campaign.streamboardSelected}">
                    <input type="checkbox" ng-model="campaign.streamboardSelected" ng-change="campaignsService.campaignChanged(campaign)">
                    {{campaign.name}}
                </label>
            </div>
            <div class="text-center streamboard-settings-header">
                <button class="btn btn-secondary btn-sm" ng-click="clearButton()">Clear donations list</button>
            </div>
        </div>
    </div>
</div>
