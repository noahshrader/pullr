<?php

use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
/**@var $regionsNumber integer */

$class = 'regionsNumber' . $regionsNumber;
?>
<div class="regionsContainer <?= $class ?>" ng-controller="RegionsCtrl">
    <div class="region" ng-repeat="region in regionsService.regions"
         ng-style="{'background-color': region.backgroundColor}"
         id='region-{{region.regionNumber}}'>
        <div ng-show="region.toShow.alert.message && (region.widgetType == 'widget_alerts')" class="widget-alerts"                 
                draggable-widget="region.widgetAlerts" 
                draggable-region="region" 
                draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
                draggable-config="{containment:'.region'}"
                interaction
                draggable>
            <!-- if alert message is shown -->
        
                <img ng-src="{{region.toShow.alert.image}}" 
                    ng-hide='region.toShow.alert.preference.hideAlertImage' 
                    draggable-widget="region.widgetAlerts" 
                    draggable-region="region" 
                    draggable-fields="{widgetLeftAttribute:'imagePositionX', widgetTopAttribute:'imagePositionY'}" 
                    interaction
                    draggable>
          
            <div 
                    ng-style="{'color': region.toShow.alert.preference.fontColor, 'font-size': region.toShow.alert.preference.fontSize, 'font-family': region.toShow.alert.preference.fontStyle}" 
                    ng-hide="region.toShow.alert.preference.hideAlertText"
                    draggable-widget="region.widgetAlerts" 
                    draggable-region="region" 
                    draggable-fields="{widgetLeftAttribute:'massagePositionX', widgetTopAttribute:'messagePositionY'}" 
                    interaction
                    draggable>
                {{region.toShow.alert.message}}
            </div>
        </div>
        <?= $this->render('activity-feed/activity-feed') ?>
        <?= $this->render('campaign-bar/campaign-bar') ?>
    </div>
</div>
