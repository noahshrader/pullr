<?
use frontend\models\streamboard\StreamboardRegion;
use frontend\models\streamboard\WidgetCampaignBar;
?>

<div id="campaign-bar" class="resize drag campaign-conatiner" ng-show="region.widgetType == '<?= StreamboardRegion::WIDGET_CAMPAIGN_BAR ?>'"
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
         draggable-config="{containment: getCampaignBarSelector(region)}"
         draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}"          
         draggable>
        Raised<span>${{number_format(campaignsService.campaigns[region.widgetCampaignBar.campaignId].amountRaised,2)}}</span>
    </div>
    <!-- Alerts -->
    <div ng-if="region.widgetCampaignBar.alertsEnable" child-scope
         ng-show='region.toShow.alert.message != null'
         ng-init="alertsModule = region.widgetCampaignBar.alertsModule"         
         class="bar-alert"          
         ng-class='region.toShow.alert.animationDirection'
         ng-style='{"background-image":getCampaignBackgroundStyle(region.toShow.alert.background),"background-size":"cover","text-align":"center","height":"100%","width":"100%"}'
         >

<!--        <div  class="bar-alert-wrap">-->
                <div draggable-widget="region.widgetCampaignBar.alertsModule"
                 interaction
                 draggable
                 draggable-region="region"
                 draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}"
                 draggable-config="{containment: getCampaignBarSelector(region)}"
                 ng-class="{fontUppercase: region.widgetCampaignBar.alertsModule.fontUppercase, textShadow: region.widgetCampaignBar.alertsModule.textShadow}"
                 ng-style="{'color': alertsModule.fontColor, 'font-size': alertsModule.fontSize, 'font-family': alertsModule.fontStyle, 'font-weight': alertsModule.fontWeight, 'background-color': alertsModule.backgroundColor, 'text-align': alertsModule.textAlignment}"
                 class="resize"
                 resizable
                 resizable-config="{minWidth:60, minHeight:60, containment:'.bar-alert'}"
                 resizable-callback="onResizeCampaignBarAlert"
                 resizable-region="region"
                 resizable-size="{width:region.widgetCampaignBar.alertsModule.messageWidth, height: region.widgetCampaignBar.alertsModule.messageHeight}"
                 >
                    <span ng-bind-html="formatMsgHtml(region.toShow.alert.message)">
                    </span>
                 </div>
<!--        </div>-->

    </div>
    <!-- Messages -->
    <div ng-if="region.widgetCampaignBar.messagesEnable" 
         class='campaign-rotating-message resize'
         ng-show='region.toShow.alert.message == null'
         interaction
         draggable-widget="region.widgetCampaignBar.messagesModule" 
         draggable-region="region" 
         draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
         draggable-config="{containment:'parent'}"
         draggable
         resizable
         resizable-config="{minWidth:60, minHeight:60, containment:'.campaign-conatiner'}"
         resizable-callback="onResizeCampaignBarMessage"
         resizable-region="region"
         resizable-size="{width:region.widgetCampaignBar.messagesModule.messageWidth, height: region.widgetCampaignBar.messagesModule.messageHeight}"

        >
        <div rotating-messages
             messages-module="region.widgetCampaignBar.messagesModule"
             rotation-speed="region.widgetCampaignBar.messagesModule.rotationSpeed"
             ng-class='{fontUppercase: region.widgetCampaignBar.fontUppercase, textShadow: region.widgetCampaignBar.textShadow}'
             style="width:100%;"
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

