<?
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBar;
?>

<div id="campaign-bar" ng-show="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'"
     resizable='{"minHeight": <?= WidgetCampaignBar::MIN_HEIGHT ?>, "minWidth": <?= WidgetCampaignBar::MIN_WIDTH ?>}'
     resizable-path="widgetCampaignBar"
     draggable position-container="region.widgetCampaignBar" region="region"
     background='{{region.widgetCampaignBar.background}}'
     ng-style="{'color': region.widgetCampaignBar.fontColor, 'font-size': region.widgetCampaignBar.fontSize, 'font-family': region.widgetCampaignBar.fontStyle, 'background-color': region.widgetCampaignBar.backgroundColor,'background-image': getCampaignBackgroundStyle(region.widgetCampaignBar.background)}">
    <div ng-if="region.widgetCampaignBar.campaignId" draggable position-container="region.widgetCampaignBar.currentTotalModule" region="region">
        Current total: ${{number_format(campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised)}}
    </div>
    <div ng-if="region.widgetCampaignBar.alertsEnable" child-scope
         ng-init="alertsModule = region.widgetCampaignBar.alertsModule"
            position-container="region.widgetCampaignBar.alertsModule" 
         region="region"
         ng-class='region.toShow.alert.animationDirection'
         ng-style='{"background-image":getCampaignAlertBackgroundStyle(region.widgetCampaignBar.alertsModule.background),"background-size":"cover"}'>

        <div
            ng-style="{'color': alertsModule.fontColor, 'font-size': alertsModule.fontSize, 'font-family': alertsModule.fontStyle, 'background-color': alertsModule.backgroundColor}">
                {{region.toShow.alert.message}}
        </div>
    </div>
    <div ng-if="region.widgetCampaignBar.messagesEnable" draggable position-container="region.widgetCampaignBar.messagesModule" region="region">
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
