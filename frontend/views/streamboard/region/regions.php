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
         id='region-{{region.regionNumber}}'
         interaction
         resizable
         resizable-config="{animate: false, handles:'n', create: onRegionResizeCreate, stop: onRegionResizeStop}"
         resizable-condition="region.regionNumber == 2"
        >
        <? echo $this->render('single-region'); ?>
    </div>
</div>
