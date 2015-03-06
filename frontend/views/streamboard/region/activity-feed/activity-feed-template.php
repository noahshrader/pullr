<script type='text/ng-template' id='activity-feed.html'>
    <!--angular-marquee container-->
    <div simple-marquee
    marquee-content='marqueeContent'
    marquee-name='region.regionNumber'
    duration="region.widgetDonationFeed.scrollSpeed"
    scroll="region.widgetDonationFeed.scrolling"
    ng-style="{'color': region.widgetDonationFeed.fontColor, 'font-size': region.widgetDonationFeed.fontSize, 'font-family': region.widgetDonationFeed.fontStyle, 'font-weight': region.widgetDonationFeed.fontWeight}"
    ng-class='{fontUppercase: region.widgetDonationFeed.fontUppercase, textShadow: region.widgetDonationFeed.textShadow}'
      >

    </div>
</script>
