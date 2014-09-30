(function () {
    var app = angular.module('pullr.streamboard.regionsPanels', ['pullr.streamboard.stream',
        'pullr.streamboard.regions', 'pullr.streamboard.alertMediaManager', 'pullr.streamboard.donations', 'pullr.streamboard.campaigns']);
    app.controller('RegionsCtrl', function ($scope, stream, regions, $interval, alertMediaManager, donations, campaigns) {
        $scope.streamService = stream;
        $scope.regionsService = regions;
        $scope.donationsService = donations;
        $scope.campaignsService = campaigns;

        $scope.regionsService.ready(function () {
            requireAllFonts();
            /*whenever regions are changes we are checking that we have right fonts*/
            $scope.$watch('regionsService.regions', requireAllFonts, true);

            /*we make a delay as streamboard may still be loading, even if regions are ready*/
            $interval(function () {
                $.each($scope.regionsService.regions, function (index, region) {
                    /*creating namespace for showing data*/
                    region.toShow = {alert: {
                    }};
                    $interval(function () {
                        showAlert(region)
                    }, 1, 1);
                });
            }, 4000, 1);
        })
        function requireAllFonts(){
            for (var key in regions.regions){
                var region = regions.regions[key];
                window.requireGoogleFont(region.widgetAlerts.followersPreference.fontStyle);
                window.requireGoogleFont(region.widgetAlerts.donationsPreference.fontStyle);
                window.requireGoogleFont(region.widgetAlerts.subscribersPreference.fontStyle);
                window.requireGoogleFont(region.widgetDonationFeed.fontStyle);
                window.requireGoogleFont(region.widgetCampaignBar.fontStyle);
            }
        }
        function capitaliseFirstLetter(string)
        {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function showAlert(region) {
            var stream = $scope.streamService.streams[region.regionNumber];
            var notification = false;
            if (region.widgetType == 'widget_alerts') {
                while (stream.length > 0 && notification == false) {
                    notification = stream.shift();
                    /**checking includeDonations, includeFollowers, includeSubscribers*/
                    if (!region.widgetAlerts['include'+capitaliseFirstLetter(notification.type)]){
                        notification = false;
                    }
                }
            } else {
                $scope.streamService.streams[region.regionNumber] = [];
            }

            if (notification) {
                console.log(['WE HAVE NOTIFICATION FOR REGION ' + region.regionNumber]);
                console.log(notification);
                var toShow = region.toShow.alert;
                toShow.message = notification.message;
                toShow.preference = region.widgetAlerts[notification.type + 'Preference'];
                window.requireGoogleFont(toShow.preference.fontStyle);

                var preference = toShow.preference;
                toShow.image = alertMediaManager.getImageUrl(preference.image, preference.imageType);

                alertMediaManager.playSound(preference.sound, preference.soundType, preference.volume);

                $interval(function () {
                    /**@todo check animationDuration*/
                    hideAlert(region);
                }, preference.animationDuration * 1000, 1);
            } else {
                $interval(function () {
                    showAlert(region);
                }, 100, 1);
            }
        }

        function hideAlert(region) {
            region.toShow.alert = {};
            $interval(function () {
                showAlert(region);
            }, 1000, 1)
        }
    });
})()
