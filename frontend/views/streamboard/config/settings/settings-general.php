<?
use yii\helpers\Url;

?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-secondary btn-sm" ng-click="clearButton()">Clear All Feeds</button>
</div>
<div class="settings pane">
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
            <a class="settingtoggle">Feeds<i class="mdi-navigation-arrow-drop-down"></i></a>
            <div class="module-settings">
                <div class="form-group red">
                    <div class="checkbox cf">
                        <label ng-class="{on:streamService.showSubscriber}">
                            <input type="checkbox" ng-model="streamService.showSubscriber" ng-change="toggleSubscriber()">
                            Show Subscribers
                        </label>
                    </div>
                    <div class="checkbox cf">
                        <label ng-class="{on:streamService.showFollower}">
                            <input type="checkbox" ng-model="streamService.showFollower" ng-change="toggleFollower()">
                            Show Followers
                        </label>
                    </div>
                </div>
                <div class="form-group red">
                    <div class="checkbox cf">
                        <label ng-class="{on:streamService.groupUser}">
                            <input type="checkbox" ng-model="streamService.groupUser" ng-change="toggleGroupUser()">
                            Enable Grouping
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
