<?
use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
$typesList = [
    ['name' => 'Alerts', 'value' => StreamboardRegion::WIDGET_ALERTS],
    ['name' => 'Campaign Bar', 'value' => StreamboardRegion::WIDGET_CAMPAIGN_BAR],
    ['name' => 'Donations Feed', 'value' => StreamboardRegion::WIDGET_DONATION_FEED],
]
?>
<h3>Region {{ region.regionNumber}}</h3>
<div class="form-group">
    <label>Background color</label>
    <input type="color" ng-model="region.backgroundColor" ng-change="regionChanged(region)">
    <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left"
       data-original-title="Some hint here"></i>
</div>
<div class="form-group" ng-init="WIDGET_TYPES=<?= htmlspecialchars(json_encode($typesList)) ?>">
    <select selectpicker ng-model="region.widgetType" ng-change="regionChanged(region)"
            ng-options="type.value as type.name for type in WIDGET_TYPES">
        <option value="">Select a widget...</option>
    </select>
</div>
<div class="widgetContainer">
    <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_ALERTS ?>'" ng-init="widget=region.widgetAlerts; initWidget()">
        <?= $this->render('region-alerts') ?>
    </div>
    <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_DONATION_FEED ?>'" ng-init="widget=region.widgetDonationFeed; initWidget()">
        <?= $this->render('region-donation-feed') ?>
    </div>
    <div ng-if="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'" ng-init="widget=region.widgetCampaignBar; initWidget()">
        <?= $this->render('campaignBar/campaign-bar') ?>
    </div>
</div>