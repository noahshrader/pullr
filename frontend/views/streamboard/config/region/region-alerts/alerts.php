<?
use yii\web\View;

/**@var $this View */
?>
<div class="form-group">
    <label>Which type of alerts do you want to include?</label>

    <div>
        <input type="checkbox" ng-model="widget.includeFollowers" ng-change="regionChanged(region)">
        <label>Followers</label>
    </div>
    <div>
        <input type="checkbox" ng-model="widget.includeSubscribers" ng-change="regionChanged(region)">
        <label>Subscribers</label>
    </div>
    <div>
        <input type="checkbox" ng-model="widget.includeDonations" ng-change="regionChanged(region)">
        <label>Donations</label>
    </div>
</div>

<div class="form-group">
    <label>Animation delay</label>
    <slider ng-model="widget.animationDelaySeconds" floor="0" ceiling="30" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{widget.animationDelaySeconds}} sec</span>
</div>
<div class="right-side-footer">
    <ul class="panel-nav paneltoggle">
        <li ng-if="widget.includeFollowers" class="active"><a data-panel="followers">Followers</a></li>
        <li ng-if="widget.includeSubscribers"><a data-panel="subscribers">Subscribers</a></li>
        <li ng-if="widget.includeDonations"><a data-panel="donations">Donations</a></li>
    </ul>
    <div class="followers_panel slidepanel pane" isolated-scope>
        <div ng-init="preference = widget.followersPreference">
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
    <div class="subscribers_panel slidepanel pane" isolated-scope>
        <div ng-init="preference = widget.subscribersPreference">
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
    <div class="donations_panel slidepanel pane" isolated-scope>
        <div ng-init="preference = widget.donationsPreference">
            <?= $this->render('alerts-preference') ?>
        </div>
    </div>
</div>