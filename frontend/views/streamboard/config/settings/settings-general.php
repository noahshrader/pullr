<?
use yii\helpers\Url;

?>
<div class="campaigns_list_panel pane">
    <div class="settings-wrap">
        <div class="campaigns_list module">
            <div class="form-group">
                <h5>Campaigns</h5>
                <div class="checkbox cf" ng-repeat="campaign in campaignsService.campaigns">
                    <label ng-class="{on:campaign.streamboardSelected}">
                        <input type="checkbox" ng-model="campaign.streamboardSelected" ng-change="campaignsService.campaignChanged(campaign)">
                        <span>{{campaign.name}}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="campaigns_list module">
            <div class="form-group">
                <h5>Activity feed</h5>
                <div class="checkbox cf">
                    <label ng-class="{on:streamService.showSubscriber}">
                        <input type="checkbox" ng-model="streamService.showSubscriber" ng-change="streamService.toggleSubscriber()">
                        <span>Show Twitch subscribers</span>
                    </label>
                </div>
                <div class="checkbox cf">
                    <label ng-class="{on:streamService.showFollower}">
                        <input type="checkbox" ng-model="streamService.showFollower" ng-change="streamService.toggleFollower()">
                        <span>Show Twitch followers</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="module">
            <div class="text-center form-group">
                <button class="btn btn-secondary btn-sm" ng-click="clearButton()">Clear donations list</button>
            </div>
        </div>
    </div>
</div>
