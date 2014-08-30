(function () {
    var app = angular.module('pullr.streamboard.regionsPanels', ['pullr.streamboard.stream',
        'pullr.streamboard.regions', 'pullr.streamboard.alertMediaManager']);
    app.controller('RegionsCtrl', function ($scope, stream, regions, $timeout, alertMediaManager) {
        $scope.streamService = stream;
        $scope.regionsService = regions;

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

                $timeout(function () {
                    /**@todo check animationDuration*/
                    hideAlert(region);
                }, preference.animationDuration * 1000);
            } else {
                $timeout(function () {
                    showAlert(region);
                }, 100);
            }
        }

        function hideAlert(region) {
            region.toShow.alert = {};
            $timeout(function () {
                showAlert(region);
            }, 1000)
        }

        $scope.regionsService.ready(function () {
            /*we make a delay as streamboard may still be loading, even if regions are ready*/
            $timeout(function () {
                $.each($scope.regionsService.regions, function (index, region) {
                    /*creating namespace for showing data*/
                    region.toShow = {alert: {
                    }};
                    $timeout(function () {
                        showAlert(region)
                    });
                });
            }, 4000);
        })
    });
})()
