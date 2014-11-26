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
    {{region.toShow.alert.message}}
</div>
<?= $this->render('activity-feed/activity-feed') ?>
<?= $this->render('campaign-bar/campaign-bar') ?>