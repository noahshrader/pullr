<?
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBar;
?>

<div id="campaign-bar" class="resize drag" ng-show="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'"
     region="region"
     background='{{region.widgetCampaignBar.background}}'
     ng-style="{'color': region.widgetCampaignBar.fontColor, 'font-size': region.widgetCampaignBar.fontSize, 'font-family': region.widgetCampaignBar.fontStyle, 'background-color': region.widgetCampaignBar.backgroundColor,'background-image': getCampaignBackgroundStyle(region.widgetCampaignBar.background)}">
    
    <!-- Current total -->
    <div class="current-total" ng-if="region.widgetCampaignBar.campaignId" draggable position-container="region.widgetCampaignBar.currentTotalModule" region="region">
        Raised<span>${{number_format(campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised)}}</span>
    </div>
    <!-- Alerts -->
    <div ng-if="region.widgetCampaignBar.alertsEnable" child-scope
         ng-show='region.toShow.alert.message != null'
         ng-init="alertsModule = region.widgetCampaignBar.alertsModule"
         position-container="region.widgetCampaignBar.alertsModule" 
         class="bar-alert" 
         region="region"
         ng-class='region.toShow.alert.animationDirection'
         ng-style='{"background-image":getCampaignBackgroundStyle(region.toShow.alert.background),"background-size":"cover","text-align":"center","height":"100%","width":"100%"}'>

        <div ng-style="{'color': alertsModule.fontColor, 'font-size': alertsModule.fontSize, 'font-family': alertsModule.fontStyle, 'background-color': alertsModule.backgroundColor}" class="bar-alert-wrap">
                <span draggable>{{region.toShow.alert.message}}</span>
        </div>
    </div>
    <!-- Messages -->
    <div ng-if="region.widgetCampaignBar.messagesEnable" 
        ng-show='region.toShow.alert.message == null'
        draggable position-container="region.widgetCampaignBar.messagesModule" region="region">
        <div rotating-messages messages-module="region.widgetCampaignBar.messagesModule"
             rotation-speed="region.widgetCampaignBar.messagesModule.rotationSpeed"></div>
    </div>
    <!-- Timers -->
    <?= $this->render('timers') ?>
    <!-- Progress -->
    <div ng-if="region.widgetCampaignBar.progressBarEnable && region.widgetCampaignBar.campaignId">
        <progressbar max="campaignsService.campaigns[region.widgetCampaignBar.campaignId].goalAmount"
                     value="campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised"
                     type="success"></progressbar>
    </div>
</div>
