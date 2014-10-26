(function () {
    var app = angular.module('pullr.streamboard.regionsPanels', ['pullr.streamboard.stream',
            'pullr.streamboard.regions', 'pullr.streamboard.alertMediaManager', 'pullr.streamboard.donations',
            'pullr.streamboard.campaigns', 'pullr.currentTime', 'pullr.countUpTimer', 'timer']).
        controller('RegionsCtrl', function ($scope, stream, regions, $interval, alertMediaManager, donations, campaigns) {
            $scope.streamService = stream;
            $scope.regionsService = regions;
            $scope.donationsService = donations;
            $scope.campaignsService = campaigns;
            $scope.scroll = true;
            $scope.duration = 1500;
            $scope.alertMediaManagerService = alertMediaManager;
            var animationEndTimer = null;

            $scope.getCampaignBackgroundStyle = function(image) {                
                var url =  'url(' + alertMediaManager.getCampaignBackgroundUrl(image) + ')';                
                return url;
            }
            $scope.getCampaignAlertBackgroundStyle = function(image) {
                var url =  'url(' + alertMediaManager.getCampaignAlertBackgroundUrl(image) + ')';                
                return url;
            }
            $scope.regionsService.ready(function () {
                requireAllFonts();
                /*whenever regions are changes we are checking that we have right fonts*/
                $scope.$watch('regionsService.regions', requireAllFonts, true);
                $scope.$watch('regionsService.regions', updateTimestamps, true);

                /*we make a delay as streamboard may still be loading, even if regions are ready*/
                $interval(function () {
                    $.each($scope.regionsService.regions, function (index, region) {                    
                        /*creating namespace for showing data*/
                        region.toShow = {alert: {
                            animationDirectionArray:[]
                        }};
                        $interval(function () {
                            showAlert(region)
                        }, 1, 1);
                    });
                }, 4000, 1);
            });

            $interval(function () {
                $scope.currentTimestamp = new Date().getTime();
            }, 1000);

            function updateTimestamps() {
                for (var key in regions.regions) {
                    var region = regions.regions[key];
                    var module = region.widgetCampaignBar.timerModule;
                    module.countDownFromTimestamp = new Date(module.countDownFrom).getTime();
                }
            }


            function requireAllFonts() {
                for (var key in regions.regions) {
                    var region = regions.regions[key];
                    window.requireGoogleFont(region.widgetAlerts.followersPreference.fontStyle);
                    window.requireGoogleFont(region.widgetAlerts.donationsPreference.fontStyle);
                    window.requireGoogleFont(region.widgetAlerts.subscribersPreference.fontStyle);
                    window.requireGoogleFont(region.widgetDonationFeed.fontStyle);
                    window.requireGoogleFont(region.widgetCampaignBar.fontStyle);
                    window.requireGoogleFont(region.widgetCampaignBar.alertsModule.fontStyle);
                }
            }

            function capitaliseFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            function showAlert(region) {
                var stream = $scope.streamService.streams[region.regionNumber];
                var notification = false;
                if (region.widgetType == 'widget_alerts' || (region.widgetType == 'widget_campaign_bar' && region.widgetCampaignBar.alertsEnable)) {
                    while (stream.length > 0 && notification == false) {
                        notification = stream.shift();
                        if (region.widgetType == 'widget_alerts') {
                            /**checking includeDonations, includeFollowers, includeSubscribers*/
                            if (!region.widgetAlerts['include' + capitaliseFirstLetter(notification.type)]) {
                                notification = false;
                            }
                        } else {
                            /**so we have widget_campaign_bar*/
                            if (!region.widgetCampaignBar.alertsModule['include' + capitaliseFirstLetter(notification.type)]) {
                                notification = false;
                            }
                        }
                    }
                } else {
                    $scope.streamService.streams[region.regionNumber] = [];
                }

                if (notification) {
                    console.log(['WE HAVE NOTIFICATION FOR REGION ' + region.regionNumber]);
                    console.log(notification);
                    var toShow = region.toShow.alert;
                    toShow.animationDirection = '';                    
                    toShow.message = notification.message;

                    if (region.widgetType == 'widget_alerts') {                        
                        toShow.preference = region.widgetAlerts[notification.type + 'Preference'];   

                        var preference = toShow.preference;
                        toShow.image = alertMediaManager.getImageUrl(preference.image, preference.imageType);                        
                        alertMediaManager.playSound(preference.sound, preference.soundType, preference.volume);
                        $interval(function () {
                            hideAlert(region);
                        }, preference.animationDuration * 1000, 1);
                        return;
                    } else {
                        /**so we have campaign bar*/
                        var alertsModule = region.widgetCampaignBar.alertsModule;
                        toShow.animationDirectionArray = alertsModule.animationDirection.split(',');
                        toShow.background = alertsModule.background;
                        toShow.image = '';
                        alertMediaManager.playSound(alertsModule.sound, alertsModule.soundType, alertsModule.volume);
                        if(toShow.animationDirectionArray.length > 1){
                            toShow.animationDirection = 'animated ' + toShow.animationDirectionArray[0];    
                            if (animationEndTimer) {
                                $interval.cancel(animationEndTimer);
                            }
                        }
                        
                        $interval(function () {
                            hideAlert(region);
                        }, alertsModule.animationDuration * 1000, 1);
                        return;
                    }
                } else {
                    $interval(function () {
                        showAlert(region);
                    }, 100, 1);
                }
            }

            function hideAlert(region) {
                
                var delay = 0;
                if (region.widgetType == 'widget_alerts') {
                    delay = region.widgetAlerts.animationDelaySeconds;
                } else if (region.widgetType =='widget_campaign_bar' && region.widgetCampaignBar.alertsEnable){
                    delay = region.widgetCampaignBar.alertsModule.animationDelay;
                }
                if (region.toShow.alert.animationDirectionArray.length > 1) {
                    region.toShow.alert.animationDirection = 'animated ' + region.toShow.alert.animationDirectionArray[1];
                    animationEndTimer = $interval(function(){
                        region.toShow.alert.message = null;    
                        region.toShow.alert.animationDirectionArray = [];    
                        region.toShow.alert.background = '';
                    }, 500, 1);
                } else {
                    region.toShow.alert.animationDirection = '';
                    region.toShow.alert.message = null;
                    region.toShow.alert.image = '';
                }
                
                $interval(function () {
                    showAlert(region);
                }, delay * 1000, 1)
            }
        });
})()
