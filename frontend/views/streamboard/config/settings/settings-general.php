<?
use yii\helpers\Url;

?>

<div class="text-center streamboard-settings-header">
    <button class="btn btn-secondary btn-sm" ng-click="clearButton()">Clear donations list</button>
</div>
<div class="campaigns_list_panel pane">
    <div class="settings-wrap">
        <div class="campaigns_list">
            <h3>Campaigns</h3>

            <div ng-repeat="campaign in campaignsService.campaigns">
                <label>
                    <input type="checkbox" ng-model="campaign.streamboardSelected" ng-change="campaignsService.campaignChanged(campaign)">
                    {{campaign.name}}
                </label>
                <br>
            </div>
        </div>
    </div>
</div>
