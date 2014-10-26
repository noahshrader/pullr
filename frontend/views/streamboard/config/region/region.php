<?
use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
$typesList = [
    ['name' => 'Alerts', 'value' => StreamboardRegion::WIDGET_ALERTS],
    ['name' => 'Campaign Bar', 'value' => StreamboardRegion::WIDGET_CAMPAIGN_BAR],
    ['name' => 'Activity Feed', 'value' => StreamboardRegion::WIDGET_DONATION_FEED],
]
?>
<div class="regions-panel pane">
    <h4 class="panel-head">Region {{ region.regionNumber}} <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="region.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':region.backgroundColor}"></h4>
    <div class="settings-wrap">
        <div class="module form-group" ng-init="WIDGET_TYPES=<?= htmlspecialchars(json_encode($typesList)) ?>">
            <h5>Widget</h5>
            <select ui-select2="{minimumResultsForSearch: -1}" ng-model="region.widgetType" ng-change="regionChanged(region)" data-placeholder="Select one..." class="widget-select">
                <option value=""></option>
                <option ng-repeat="type in WIDGET_TYPES" value="{{type.value}}">{{type.name}}</option>
            </select>
        </div>
        <div class="widgetContainer">
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_ALERTS ?>'" ng-init="widget=region.widgetAlerts; initWidget()">
                <?= $this->render('region-alerts/alerts') ?>
            </div>
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'" ng-init="widget=region.widgetCampaignBar; initWidget()">
                <?= $this->render('campaign-bar/campaign-bar') ?>
            </div>
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_DONATION_FEED ?>'" ng-init="widget=region.widgetDonationFeed; initWidget()">
                <?= $this->render('region-donation-feed') ?>
            </div>
        </div>
    </div>
</div>