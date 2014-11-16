<?
use frontend\models\streamboard\StreamboardRegion;
?>

    <!--angular-marquee container-->
    <div ng-class='{marqueeStop: ! region.widgetDonationFeed.scrolling}' class='marquee-container'
         draggable-widget="region.widgetDonationFeed" 
         draggable-region="region" 
         draggable-config="{containment:'parent'}"
         draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}" 
         interaction
         draggable
         resizable  
         resizable-config="{minWidth:60, minHeight:60, containment:'.region', handles:'e,w'}"
         resizable-callback="onResizeActivityFeed"
         resizable-region="region",
         resizable-size="{width:region.widgetDonationFeed.width, height: region.widgetDonationFeed.height}"
         ng-if='region.widgetType == <?= htmlspecialchars(json_encode(StreamboardRegion::WIDGET_DONATION_FEED)) ?>'
        >
        <div simple-marquee 
        marquee-name='region.regionNumber'
        duration="region.widgetDonationFeed.scrollSpeed"
        scroll="region.widgetDonationFeed.scrolling"                        
        ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle, 'font-weight': region.widgetDonationFeed.fontWeight}"
        ng-class='{fontUppercase: region.widgetDonationFeed.fontUppercase}'
          >
            <span>
                <span ng-if=' ! streamService.groupUser'>
                    <span
                        ng-repeat="donation in donationsService.donations | orderBy: amount | donationsFilterToSelectedCampaigns "
                        class="commaAfter">
                    <span>
                        &nbsp;{{donation.name}} (${{number_format(donation.amount)}})<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span
                        ng-repeat="follower in donationsService.followers"
                        class="commaAfter" ng-if='streamService.showFollower'>
                    <span>
                        &nbsp;{{follower.display_name}} (followed)<!--removing space for .commaAfter
                    --></span>
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        class="commaAfter" ng-if='streamService.showSubscriber'>
                    <span>
                        &nbsp;{{subscriber.display_name}} (subscribed)<!--removing space for .commaAfter
                    --></span>
                    </span>
                    
                    <span ng-if="(donationsService.donations | donationsFilterToSelectedCampaigns).length == 0 && ( ! streamService.showSubscriber || donationsService.subscribers.length == 0 ) && ( ! streamService.showFollower || donationsService.followers.length == 0 ) ">
                        <span ng-if="region.widgetDonationFeed.noDonationMessage">{{region.widgetDonationFeed.noDonationMessage}}</span>
                        <span ng-if="!region.widgetDonationFeed.noDonationMessage">No activity!</span>
                    </span> 
                </span> 
                <span ng-if='streamService.groupUser'>
                    <span ng-repeat="groupDonation in donationsService.groupDonations"
                        class="commaAfter grouped">
                        <span ng-repeat='donation in groupDonation.items' class="commaAfter"> &nbsp;{{donation.name}}</span> (${{number_format(groupDonation.amount)}})
                    </span>

                    <span
                        ng-repeat="subscriber in donationsService.subscribers"
                        ng-if='streamService.showSubscriber'
                        class="commaAfter">
                        
                        &nbsp;{{subscriber.display_name}}<!--removing space for .commaAfter
                    -->
                        <span class="commaAfter" ng-show='$last'>(subscribed)</span>
                    </span> 

                    <span
                        ng-repeat="follower in donationsService.followers"
                        ng-if='streamService.showFollower' class="commaAfter">
                        
                            &nbsp;{{follower.display_name}}<!--removing space for .commaAfter
                        -->
                        <span class="commaAfter" ng-show='$last'>(followed)</span>
                    </span> 

                    
                </span>     
            </span>         
        </div>

    </div>
