<?
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBar;
?>

<div id="campaign-bar" ng-show="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'"
     resizable='{"minHeight": <?= WidgetCampaignBar::MIN_HEIGHT ?>, "minWidth": <?= WidgetCampaignBar::MIN_WIDTH ?>, "defaultWidth": <?= WidgetCampaignBar::DEFAULT_WIDTH ?>}'
     draggable position-container="region.widgetCampaignBar" region="region"
     ng-style="{'color': region.widgetCampaignBar.fontColor, 'font-size': region.widgetCampaignBar.fontSize, 'font-family': region.widgetCampaignBar.fontStyle, 'background-color': region.widgetCampaignBar.backgroundColor}">
    <div ng-if="region.widgetCampaignBar.alertsEnable" child-scope
         ng-init="alertsModule = region.widgetCampaignBar.alertsModule">
        <div
            ng-style="{'color': alertsModule.fontColor, 'font-size': alertsModule.fontSize, 'font-family': alertsModule.fontStyle, 'background-color': alertsModule.backgroundColor}">
                {{region.toShow.alert.message}}
        </div>
    </div>
    <div ng-if="region.widgetCampaignBar.messagesEnable">
        <div rotating-messages messages-module="region.widgetCampaignBar.messagesModule"
             rotation-speed="region.widgetCampaignBar.messagesModule.rotationSpeed"></div>
    </div>
    <?= $this->render('timers') ?>
    <div ng-if="region.widgetCampaignBar.progressBarEnable && region.widgetCampaignBar.campaignId">
        <progressbar max="campaignsService.campaigns[region.widgetCampaignBar.campaignId].goalAmount"
                     value="campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised"
                     type="success"></progressbar>
    </div>
</div>
