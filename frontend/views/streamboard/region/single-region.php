<?php

use yii\web\View;
use frontend\models\streamboard\StreamboardRegion;

/**@var $this View */
/**@var $regionsNumber integer */
?>
        <!-- Donation Alert -->        
        <div ng-init="preference = region.widgetAlerts.donationsPreference" child-scope>
            <?= $this->render('partials/region-alert') ?>      
        </div>

        <!-- Follow Alert -->        
        <div ng-init="preference = region.widgetAlerts.followersPreference" child-scope>
            <?= $this->render('partials/region-alert') ?>      
        </div>

        <!-- Subscriber Alert -->        
        <div ng-init="preference = region.widgetAlerts.subscribersPreference" child-scope>
            <?= $this->render('partials/region-alert') ?>
        </div>

        <!-- Show Tags -->

        <!-- lastSubscriberWidget -->
        <div ng-show="region.widgetTags.lastSubscriber" ng-init="tagPreference = region.widgetTags.lastSubscriberWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Last Subscriber&nbsp;:&nbsp; <span id="last_subscriber" ng-show="donationsService.subscribers.length > 0">{{donationsService.subscribers[0].display_name}}</span>       
            ')) ?>
        </div>

        <!-- lastFollowerWidget -->
        <div ng-show="region.widgetTags.lastFollower" ng-init="tagPreference = region.widgetTags.lastFollowerWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Last Follower&nbsp;:&nbsp;<span id="last_follower" ng-show="donationsService.followers.length > 0">{{donationsService.followers[0].display_name}}</span>
            ')) ?>
        </div>

        <!-- lastDonorWidget -->
        <div ng-show="region.widgetTags.lastDonor" ng-init="tagPreference = region.widgetTags.lastDonorWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Last Donor&nbsp;:&nbsp;<span id="last_donor">{{donationsService.stats.last_donor.name}}</span>
            ')) ?>
        </div>

        <!-- lastDonorAndDonationWidget -->
        <div ng-show="region.widgetTags.lastDonorAndDonation" ng-init="tagPreference = region.widgetTags.lastDonorAndDonationWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Last Donor/Donation&nbsp;:&nbsp;<span id="last_donor_donation">{{donationsService.stats.last_donor.name}} (${{number_format(donationsService.stats.last_donor.amount)}})</span>
            ')) ?>
        </div>

        <!-- largestDonationWidget -->
        <div ng-show="region.widgetTags.largestDonation" ng-init="tagPreference = region.widgetTags.largestDonationWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Largest Donation&nbsp;:&nbsp;<span id="largest_donation">${{number_format(donationsService.stats.top_donation.amount)}} ({{donationsService.stats.top_donation.displayName}})</span>
            ')) ?>
        </div>

        <!-- topDonorWidget -->
        <div ng-show="region.widgetTags.topDonor" ng-init="tagPreference = region.widgetTags.topDonorWidget" child-scope>
            <?= $this->render('partials/region-tag', array('content' =>'
                Top Donor&nbsp;:&nbsp;<span id="top_donor">{{donationsService.stats.top_donors[0].name}} (${{number_format(donationsService.stats.top_donors[0].amount)}})</span>
            ')) ?>
        </div>

<?= $this->render('activity-feed/activity-feed') ?>
<?= $this->render('campaign-bar/campaign-bar') ?>