<?php

use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
/**@var $regionsNumber integer */

$class = 'regionsNumber' . $regionsNumber;
?>
<div class="regionsContainer <?= $class ?>" ng-controller="RegionsCtrl">
    <div class="region resizable-v text-center" ng-repeat="region in regionsService.regions"
         ng-style="{'background-color': region.backgroundColor}">
        <div ng-show="region.toShow.alert.message" class="movable">
            <!-- if alert message is showed-->
            <img ng-src="{{region.toShow.alert.image}}">
            <div
                ng-style="{'color': region.toShow.alert.preference.fontColor, 'font-size': region.toShow.alert.preference.fontSize, 'font-family': region.toShow.alert.preference.fontStyle}">
                {{region.toShow.alert.message}}
            </div>
        </div>
        <div ng-show='region.widgetType == <?= htmlspecialchars(json_encode((StreamboardRegion::WIDGET_DONATION_FEED))) ?>' class="movable donation-stream-scroll"
            ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle}">
            <span ng-repeat="donation in donationsService.donations | donationsFilterToSelectedCampaigns | limitTo: 20">
                {{donation.displayName}} (${{number_format(donation.amount)}})
            </span>
            <span ng-show="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0">
            <span>
                {{region.widgetDonationFeed.noDonationMessage}}
            </span>
        </div>
    </div>
</div>
