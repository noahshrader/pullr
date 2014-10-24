<?
use frontend\models\streamboard\StreamboardRegion;
?>

<div
    ng-if='region.widgetType == <?= htmlspecialchars(json_encode(StreamboardRegion::WIDGET_DONATION_FEED)) ?>'
    class="">
    <!--angular-marquee container-->
    <div>
        <div ng-class='{marqueeStop: ! region.widgetDonationFeed.scrolling}' class='marquee-container'>
            <div simple-marquee 
            marquee-name='region.regionNumber'
            duration="region.widgetDonationFeed.scrollSpeed"
            scroll="region.widgetDonationFeed.scrolling"                        
            ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle}"
              >
                <span>
                    <span
                        ng-repeat="donation in donationsService.donations | orderBy: amount | donationsFilterToSelectedCampaigns  | limitTo: 20"
                        class="commaAfter">
                    <span>
                        &nbsp;{{donation.displayName}} (${{number_format(donation.amount)}})<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span
                        ng-repeat="follower in donationsService.followers | limitTo: 20"
                        class="commaAfter" ng-show='streamService.showFollower'>
                    <span>
                        &nbsp;{{follower.display_name}} (followed)<!--removing space for .commaAfter
                    --></span>
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers | limitTo: 20"
                        class="commaAfter" ng-show='streamService.showSubscriber'>
                    <span>
                        &nbsp;{{subscriber.display_name}} (subscribed)<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0">
                        <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                        <span ng-if="!region.widgetDonationFeed.noDonationMessage">No activity!</span>
                    </span> 
                </span>               
            </div>
    
        </div>
    </div>
</div>