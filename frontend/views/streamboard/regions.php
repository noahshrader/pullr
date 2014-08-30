<?php

use yii\web\View;

/**@var $this View */
/**@var $regionsNumber integer */

$class = 'regionsNumber' . $regionsNumber;
?>
<div class="regionsContainer <?=$class?>" ng-controller="RegionsCtrl">
    <div class="region resizable-v text-center" ng-repeat="region in regionsService.regions" ng-style="{'background-color': region.backgroundColor}">
       <div ng-show="region.toShow.alert.message" class="movable">
          <!-- if alert message is showed-->
           <img ng-src="{{region.toShow.alert.image}}">
           <div ng-style="{'color': region.toShow.alert.preference.fontColor, 'font-size': region.toShow.alert.preference.fontSize, 'font-family': region.toShow.alert.preference.fontStyle}">
               {{region.toShow.alert.message}}
           </div>
       </div>
    </div>
</div>
