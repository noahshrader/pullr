<?
use yii\web\View;

/**@var $this View */
?>
<div class="module">
    <div class="form-group">
        <h5>Alert Types</h5>
        <div class="checkbox">
            <label ng-class="{on:widget.includeDonations}">
                <input type="checkbox" ng-model="widget.includeDonations" ng-change="regionChanged(region)">
                Donations
            </label>
        </div>
        <div class="checkbox">
            <label ng-class="{on:widget.includeFollowers}">
                <input type="checkbox" ng-model="widget.includeFollowers" ng-change="regionChanged(region)">
                Followers
            </label>
        </div>
        <div class="checkbox">
            <label ng-class="{on:widget.includeSubscribers}">
                <input type="checkbox" ng-model="widget.includeSubscribers" ng-change="regionChanged(region)">
                Subscribers
            </label>
        </div>
    </div>
</div>
<div class="module">
    <div class="form-group">
        <h5>Delay <span class="slider-value value">{{widget.animationDelaySeconds}} sec</span></h5>
        <slider ng-model="widget.animationDelaySeconds" floor="0" ceiling="30" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
</div>
<div class="right-side-footer" ng-show='widget.includeDonations || widget.includeFollowers || widget.includeSubscribers'>
    <ul class="panel-nav paneltoggle">
        <li ng-if="widget.includeDonations" class="panel-link"><a data-panel="donations"><i class="icon-coin"></i>Donations</a></li>
        <li ng-if="widget.includeFollowers" class="panel-link"><a data-panel="followers"><i class="icon-heart"></i>Followers</a></li>
        <li ng-if="widget.includeSubscribers" class="panel-link"><a data-panel="subscribers"><i class="icon-star"></i>Subscribers</a></li>
    </ul>
    <div class="donations_panel slidepanel pane" child-scope>
        <div ng-init="preference = widget.donationsPreference">
            <h4 class="panel-title">
                Donation Alerts 
                <button class="btn btn-sm" ng-click="streamService.testData(preference.preferenceType, 1, region.regionNumber)">Test</button>
            </h4>            
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
    <div class="followers_panel slidepanel pane" child-scope>
        <div ng-init="preference = widget.followersPreference">
            <h4 class="panel-title">
                Follower Alerts 
                <button class="btn btn-sm" ng-click="streamService.testData(preference.preferenceType, 1, region.regionNumber)">Test</button>
            </h4>
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
    <div class="subscribers_panel slidepanel pane" child-scope>
        <div ng-init="preference = widget.subscribersPreference">
            <h4 class="panel-title">
                Subscriber Alerts 
                <button class="btn btn-sm" ng-click="streamService.testData(preference.preferenceType, 1, region.regionNumber)">Test</button>
            </h4>
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
</div>