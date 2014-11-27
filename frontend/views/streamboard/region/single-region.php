<?php

use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
/**@var $regionsNumber integer */
?>
<div ng-show="region.toShow.alert.message && region.widgetType == 'widget_alerts'"
    class='widget-alerts'
    interaction
    draggable
    draggable-widget="region.widgetAlerts" 
    draggable-region="region" 
    draggable-config="{containment:getRegionSelector(region) }"
    draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
    resizable
    resizable-config="{containment: getRegionSelector(region), aspectRatio: true}"
    resizable-callback="onResizeAlertImage"     
    resizable-region="region" 
    resizable-size="{width:region.widgetAlerts.imageWidth, height: region.widgetAlerts.imageHeight}"
    >
    <!-- if alert message is shown -->
     
    <img ng-src="{{region.toShow.alert.image}}" 
        ng-show='region.toShow.alert.image != null'
        ng-hide='region.toShow.alert.preference.hideAlertImage' 
        ng-class='region.toShow.alert.animationDirection'
    />
</div>
<div 
    class="widget-alerts-message {{region.toShow.alert.animationDirection}}"
    ng-show="region.toShow.alert.message && region.widgetType == 'widget_alerts'"
    ng-style="{'color': region.toShow.alert.preference.fontColor, 'font-size': region.toShow.alert.preference.fontSize, 'font-family': region.toShow.alert.preference.fontStyle, 'font-weight': region.toShow.alert.preference.fontWeight, 'position':'absolute', 'text-align':region.toShow.alert.preference.textAlignment}" 
    ng-class='{fontUppercase:region.toShow.alert.preference.fontUppercase}' 
    ng-hide="region.toShow.alert.preference.hideAlertText"
    interaction
    draggable
    draggable-widget="region.widgetAlerts" 
    draggable-region="region" 
    draggable-config="{containment:getRegionSelector(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"             
    resizable
    resizable-config="{containment: getRegionSelector(region)}"            
    resizable-region="region" 
    resizable-callback="onResizeAlertMessage"    
    resizable-size="{width:region.widgetAlerts.messageWidth, height: region.widgetAlerts.messageHeight}">
    <span ng-bind-html="formatMsgHtml(region.toShow.alert.message)"></span>
</div>
<!----Show Tags-------->


<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.lastSubscriberWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeLastSubscriberWidget"
    resizable-size="{width:region.widgetTags.lastSubscriberWidget.width, height: region.widgetTags.lastSubscriberWidget.height}"
    ng-show="region.widgetTags.lastSubscriber"
    >
    Last Subscriber&nbsp;:&nbsp; <span id='last_subscriber' ng-show="donationsService.subscribers.length > 0">{{donationsService.subscribers[0].display_name}}</span>            
</div>

<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.lastFollowerWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeLastFollowerWidget"
    resizable-size="{width:region.widgetTags.lastFollowerWidget.width, height: region.widgetTags.lastFollowerWidget.height}"
    ng-show="region.widgetTags.lastFollower"
    >
    Last Follower&nbsp;:&nbsp;<span id='last_follower' ng-show="donationsService.followers.length > 0">{{donationsService.followers[0].display_name}}</span>            
</div>

<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.lastDonorWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeLastDonorWidget"
    resizable-size="{width:region.widgetTags.lastDonorWidget.width, height: region.widgetTags.lastDonorWidget.height}"
    ng-show="region.widgetTags.lastDonor"
    >
    Last Donor&nbsp;:&nbsp;<span id='last_donor'>{{donationsService.stats.last_donor.name}}</span>
</div>

<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.lastDonorAndDonationWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeLastDonorAndDonationWidgetWidget"
    resizable-size="{width:region.widgetTags.lastDonorAndDonationWidget.width, height: region.widgetTags.lastDonorAndDonationWidget.height}"
    ng-show="region.widgetTags.lastDonorAndDonation"
    >
    Last Donor/Donation&nbsp;:&nbsp;<span id='last_donor_donation'>{{donationsService.stats.last_donor.name}} (${{donationsService.stats.last_donor.amount}})</span>
</div>

<div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.largestDonationWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeLargestDonationWidgetWidget"
    resizable-size="{width:region.widgetTags.largestDonationWidget.width, height: region.widgetTags.largestDonationWidget.height}"
    ng-show="region.widgetTags.largestDonation"
    >
    Largest Donation&nbsp;:&nbsp;<span id='largest_donation'>${{donationsService.stats.top_donation.amount}} ({{donationsService.stats.top_donation.displayName}})</span>
</div>

 <div
    class="widget-tags-message"            
    ng-style="{'color': region.widgetTags.fontColor, 'font-size': region.widgetTags.fontSize, 'font-family': region.widgetTags.fontStyle, 'font-weight': region.widgetTags.fontWeight, 'position':'absolute'}"
    ng-class='{fontUppercase:region.widgetTags.fontUppercase}'            
    interaction
    draggable
    draggable-widget="region.widgetTags.topDonorWidget"
    draggable-region="region"
    draggable-config="{containment:getContainmentByRegion(region)}"
    draggable-fields="{widgetLeftAttribute:'messagePositionX', widgetTopAttribute:'messagePositionY'}"
    resizable
    resizable-config="{containment: getContainmentByRegion(region)}"
    resizable-region="region"
    resizable-callback="onResizeTopDonorWidget"
    resizable-size="{width:region.widgetTags.topDonorWidget.width, height: region.widgetTags.topDonorWidget.height}"
    ng-show="region.widgetTags.topDonor"
    >
    Top Donor&nbsp;:&nbsp;<span id='top_donor'>{{donationsService.stats.top_donors[0].name}} (${{donationsService.stats.top_donors[0].amount}})</span>
</div>

<?= $this->render('activity-feed/activity-feed') ?>
<?= $this->render('campaign-bar/campaign-bar') ?>