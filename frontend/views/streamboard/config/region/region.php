<?
use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;
use common\components\Application;
use yii\helpers\Url;
use frontend\models\streamboard\Streamboard;
$user = Streamboard::getCurrentUser();
$region1Url = Url::to(['streamboard/region','streamboardToken' => $user->streamboardConfig->streamboardToken, 'regionNumber' => 1], true);
$region2Url = Url::to(['streamboard/region','streamboardToken' => $user->streamboardConfig->streamboardToken, 'regionNumber' => 2], true);
/**@var $this View */
$typesList = [
    ['name' => 'Alerts', 'value' => StreamboardRegion::WIDGET_ALERTS],
    ['name' => 'Campaign Bar', 'value' => StreamboardRegion::WIDGET_CAMPAIGN_BAR],
    ['name' => 'Activity Feed', 'value' => StreamboardRegion::WIDGET_DONATION_FEED],
];
?>
<div class="regions-panel pane" ng-class='{paddingBottom: ! hideFooter}'>
    <h4 class="panel-head">Region {{ region.regionNumber}}
        <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="region.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':region.backgroundColor}">
        <a class="capture-link mdi-action-settings-ethernet" href='<?= $region1Url; ?>' ng-if='region.regionNumber == 1' target='_blank'></a>
        <a class="capture-link mdi-action-settings-ethernet" href='<?= $region2Url; ?>' ng-if='region.regionNumber == 2' target='_blank'></a>
    </h4>
    <div class="settings-wrap">
        <div class="module" ng-init="WIDGET_TYPES=<?= htmlspecialchars(json_encode($typesList)) ?>">
            <div class="form-group">
                <h5>Widget</h5>
                <select ui-select2="{minimumResultsForSearch: -1}" ng-model="region.widgetType" ng-change="regionChanged(region)" data-placeholder="Select one..." class="widget-select">
                    <option value=""></option>
                    <option ng-repeat="type in WIDGET_TYPES" value="{{type.value}}">{{type.name}}</option>
                </select>
            </div>
        </div>
        <div class="widgetContainer">
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_ALERTS ?>'" ng-init="widget=region.widgetAlerts; initWidget();toggleFooter(region)">
                <?= $this->render('region-alerts/alerts') ?>
            </div>
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'" ng-init="widget=region.widgetCampaignBar; initWidget();toggleFooter(region)">
                <?= $this->render('campaign-bar/campaign-bar') ?>
            </div>
            <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_DONATION_FEED ?>'" ng-init="widget=region.widgetDonationFeed; initWidget();toggleFooter(region)">
                <?= $this->render('region-donation-feed') ?>
            </div>
        </div>
    </div>
</div>