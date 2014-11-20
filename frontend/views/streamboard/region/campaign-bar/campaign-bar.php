<?
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBar;
?>

<div id="campaign-bar" class="resize drag" ng-show="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'"
     region="region"
     background='{{region.widgetCampaignBar.background}}'
     ng-style="{'color': region.widgetCampaignBar.fontColor, 'font-size': region.widgetCampaignBar.fontSize, 'font-family': region.widgetCampaignBar.fontStyle, 'font-weight': region.widgetCampaignBar.fontWeight, 'background-color': region.widgetCampaignBar.backgroundColor,'background-image': getCampaignBackgroundStyle(region.widgetCampaignBar.background)}"
     interaction
     draggable
     draggable-widget="region.widgetCampaignBar" 
     draggable-region="region" 
     draggable-config="{containment:getRegionSelector(region)}"
     draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}"           
     resizable
     resizable-config="{minWidth:60, minHeight:60, containment:'.region'}"
     resizable-callback="onResizeCampaignBar"
     resizable-region="region",
     resizable-size="{width:region.widgetCampaignBar.width, height: region.widgetCampaignBar.height}"
     >
    
    <!-- Current total -->
    <div class="current-total" ng-if="region.widgetCampaignBar.campaignId" 
         interaction
         draggable-widget="region.widgetCampaignBar.currentTotalModule" 
         draggable-region="region" 
         draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}"          
         draggable>
        Raised<span>${{campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised}}</span>
    </div>
    <!-- Alerts -->
    <div ng-if="region.widgetCampaignBar.alertsEnable" child-scope
         ng-show='region.toShow.alert.message != null'
         ng-init="alertsModule = region.widgetCampaignBar.alertsModule"         
         class="bar-alert"          
         ng-class='region.toShow.alert.animationDirection'
         ng-style='{"background-image":getCampaignBackgroundStyle(region.toShow.alert.background),"background-size":"cover","text-align":"center","height":"100%","width":"100%"}'         
         >

        <div ng-style="{'color': alertsModule.fontColor, 'font-size': alertsModule.fontSize, 'font-family': alertsModule.fontStyle, 'font-weight': alertsModule.fontWeight, 'background-color': alertsModule.backgroundColor}" class="bar-alert-wrap">
                <span draggable-widget="region.widgetCampaignBar.alertsModule" 
                 interaction
                 draggable-region="region" 
                 draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
                 draggable-config="{containment: getCampaignBarSelector(region)}"
                 ng-class="{fontUppercase: region.widgetCampaignBar.alertsModule.fontUppercase}"
                 draggable>{{region.toShow.alert.message}}</span>
        </div>

    </div>
    <!-- Messages -->
    <div ng-if="region.widgetCampaignBar.messagesEnable" 
         class='campaign-rotating-message'
         ng-show='region.toShow.alert.message == null'
         interaction
         draggable-widget="region.widgetCampaignBar.messagesModule" 
         draggable-region="region" 
         draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
         draggable-config="{containment:'parent'}"
         draggable>
        <div rotating-messages 
             messages-module="region.widgetCampaignBar.messagesModule"
             rotation-speed="region.widgetCampaignBar.messagesModule.rotationSpeed"
             ng-class='{fontUppercase: region.widgetCampaignBar.fontUppercase}'
             ></div>
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

