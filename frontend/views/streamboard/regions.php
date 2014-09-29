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
        <div
            ng-if='region.widgetType == <?= htmlspecialchars(json_encode((StreamboardRegion::WIDGET_DONATION_FEED))) ?>'
            class="">
            <!--angular-marquee container-->
            <div>
                <div scroll="region.widgetDonationFeed.scrolling">
                    <div angular-marquee
                         ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle}">
                        <span
                            ng-repeat="donation in donationsService.donations | donationsFilterToSelectedCampaigns | limitTo: 20"
                            class="commaAfter">
                        <span>
                            &nbsp;{{donation.displayName}} (${{number_format(donation.amount)}})<!--removing space for .commaAfter
                        --></span>
                        </span>
                        <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0">
                            <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                            <span ng-if="!region.widgetDonationFeed.noDonationMessage">Empty activity feed</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
