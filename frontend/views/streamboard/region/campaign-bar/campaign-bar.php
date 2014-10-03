<?
use frontend\models\streamboard\StreamboardRegion;
?>

<div ng-if="region.widgetType == <?= htmlspecialchars(json_encode(StreamboardRegion::WIDGET_CAMPAIGN_BAR))?>"
     ng-style="{'color': region.widgetCampaignBar.fontColor, 'font-size': region.widgetCampaignBar.fontSize, 'font-family': region.widgetCampaignBar.fontStyle, 'background-color': region.widgetCampaignBar.backgroundColor}">
    <div ng-if="region.widgetCampaignBar.messagesEnable">
        <div rotating-messages messages-module="region.widgetCampaignBar.messagesModule" rotation-speed="region.widgetCampaignBar.messagesModule.rotationSpeed"></div>
    </div>
    <?= $this->render('timer') ?>
    <div ng-if="region.widgetCampaignBar.progressBarEnable && region.widgetCampaignBar.campaignId">
        <progressbar max="campaignsService.campaigns[region.widgetCampaignBar.campaignId].goalAmount" value="campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised" type="success"></progressbar>
    </div>
</div>
