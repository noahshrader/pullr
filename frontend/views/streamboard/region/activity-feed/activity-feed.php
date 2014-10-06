<?
use frontend\models\streamboard\StreamboardRegion;
?>

<div
    ng-if='region.widgetType == <?= htmlspecialchars(json_encode(StreamboardRegion::WIDGET_DONATION_FEED)) ?>'
    class="">
    <!--angular-marquee container-->
    <div>
        <div>
            <div angular-marquee 
            duration="region.widgetDonationFeed.scrollSpeed"
            scroll="region.widgetDonationFeed.scrolling"
                ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle}"
              >
                <span
                    ng-repeat="donation in donationsService.donations | donationsFilterToSelectedCampaigns | limitTo: 20"
                    class="commaAfter">
                <span>
                    &nbsp;{{donation.displayName}} (${{number_format(donation.amount)}})<!--removing space for .commaAfter
                --></span>
                </span>
                <div>asdasd</div>
                <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0">
                    <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                    <span ng-if="!region.widgetDonationFeed.noDonationMessage">Empty activity feed</span>
                </span>
                <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0">
                    <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                    <span ng-if="!region.widgetDonationFeed.noDonationMessage">Empty activity feed</span>
                </span>
            </div>
    
        </div>
    </div>
</div>