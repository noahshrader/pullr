<?
use frontend\models\streamboard\StreamboardRegion;
?>

<? echo $this->render('activity-feed-template'); ?>
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
    <activity-feed
        region='region'
        donations='donationsService.userDonations'
        enable-group-donation='streamService.groupUser'
        group-by='streamService.groupBase'
        group-donation-by-email='donationsService.groupDonationsByEmail'
        group-donation-by-name='donationsService.groupDonationsByName'
        followers='donationsService.followers'
        subscribers='donationsService.subscribers'
        show-follower='streamService.showFollower'
        show-subscriber='streamService.showSubscriber'
        no-donation-message='streamService.noDonationMessage'
        >
    </activity-feed>
</div>
