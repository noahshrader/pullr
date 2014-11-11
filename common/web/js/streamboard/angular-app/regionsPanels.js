(function () {
    var app = angular.module('pullr.streamboard.regionsPanels', ['pullr.streamboard.stream',
            'pullr.streamboard.regions', 'pullr.streamboard.alertMediaManager', 'pullr.streamboard.donations',
            'pullr.streamboard.campaigns', 'pullr.currentTime', 'pullr.countUpTimer', 'timer', 'simpleMarquee',
            'pullr.streamboard.twitch']).
        controller('RegionsCtrl', function ($scope, stream, regions, $interval, alertMediaManager, donations, campaigns, simpleMarqueeHelper, streamboardConfig, twitchNotification) {
            $scope.streamService = stream;
            $scope.regionsService = regions;
            $scope.donationsService = donations;
            $scope.campaignsService = campaigns;
            $scope.scroll = true;
            $scope.duration = 1500;
            $scope.alertMediaManagerService = alertMediaManager;
            $scope.streamboardConfig = streamboardConfig;
            twitchNotification.requestTwitchData();
            $interval(twitchNotification.requestTwitchData, 20000);

            var $region2 = $(".regionsContainer .region:last-child");
            var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            var isShowingNotification = false;
            $scope.getCampaignBackgroundStyle = function(image) {                
                var url =  'url(' + alertMediaManager.getCampaignBackgroundUrl(image) + ')';                
                return url;
            }
            $scope.getCampaignAlertBackgroundStyle = function(image) {
                var url =  'url(' + alertMediaManager.getCampaignAlertBackgroundUrl(image) + ')';                
                return url;
            }

            $scope.$watch('streamboardConfig.config.region2HeightPercent', function(height) {            
                if (height > 0) {
                    var $region2 = $(".regionsContainer .region:last-child");
                    $region2.height((height) + '%');
                    recalculateRegionSize();
                }
            });            

            var recalculateRegionSize = function() {     
                var $region2 = $(".regionsContainer .region:last-child");           
                var remainingSpace = (100 * parseFloat($region2.css('height')) / parseFloat($region2.parent().css('height')));
                var divOne = $region2.prev();
                var divTwoHeight = (remainingSpace) + '%';
                var divOneHeight = (100 - remainingSpace) + '%';
                $region2.height(divTwoHeight);
                $(divOne).height(divOneHeight);            
            }

            $scope.onRegionResizeCreate = function() {
                $(".regionsContainer .region:last-child").resize(function() {                
                    recalculateRegionSize();          
                });    
            }

            $scope.onRegionResizeStop = function(event, ui) {
                var $region2 = $(".regionsContainer .region:last-child");           
                streamboardConfig.setRegion2Height($region2[0].style.height);
            }

            $scope.onResizeAlertImage = function(region, event, ui) {
                region.widgetAlerts.imageWidth = ui.size.width;
                region.widgetAlerts.imageHeight = ui.size.height;
                region.widgetAlerts.positionX = ui.position.left;
                region.widgetAlerts.positionY = ui.position.top;
                regions.regionChanged(region);
            }

            $scope.getContainmentByRegion = function(region) {
                return '#region-' + region.regionNumber;
            }
            

            $scope.onResizeCampaignBar = function(region, event, ui) {                
                if (region != null) {
                    region.widgetCampaignBar.positionX = ui.position.left;
                    region.widgetCampaignBar.positionY = ui.position.top;
                    region.widgetCampaignBar.width = ui.size.width;
                    region.widgetCampaignBar.height = ui.size.height;
                    regions.regionChanged(region);    
                }                
            }

            $scope.onResizeActivityFeed = function(region, event, ui) {
                simpleMarqueeHelper.recalculateMarquee();
                region.widgetDonationFeed.positionX = ui.position.left;
                region.widgetDonationFeed.positionY = ui.position.top;
                region.widgetDonationFeed.width = ui.size.width;
                region.widgetDonationFeed.height = ui.size.height;
                regions.regionChanged(region);
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
                            animationDirectionArray:[],
                            isRunning: false
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
         //           isShowingNotification = true;
                    var toShow = region.toShow.alert;
                    toShow.animationDirection = '';  
                    toShow.isRunning = true;                  
                    toShow.message = notification.message;

                    if (region.widgetType == 'widget_alerts') {                        
                        toShow.preference = region.widgetAlerts[notification.type + 'Preference'];   
                        toShow.notificationType = notification.type;
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
                        $('#region-' + region.regionNumber + ' .bar-alert:eq(0)').off(animationEndEvent);
                        if(toShow.animationDirectionArray.length > 1){
                            toShow.animationDirection = 'animated ' + toShow.animationDirectionArray[0];                            
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
                } else if (region.widgetType =='widget_campaign_bar' && region.widgetCampaignBar.alertsEnable) {
                    delay = region.widgetCampaignBar.alertsModule.animationDelay;
                }
                if (region.toShow.alert.animationDirectionArray.length > 1) {
                    region.toShow.alert.animationDirection = 'animated ' + region.toShow.alert.animationDirectionArray[1];
                    $('#region-' + region.regionNumber + ' .bar-alert:eq(0)').one(animationEndEvent, function(){
                        region.toShow.alert.message = null;    
                        region.toShow.alert.animationDirectionArray = [];    
                        region.toShow.alert.background = '';
                        $interval(function () {
                            showAlert(region);
                        }, delay * 1000, 1);       
                    });
                    
                } else {
                    region.toShow.alert.animationDirection = '';
                    region.toShow.alert.message = null;
                
                    isShowingNotification = false;
                    $interval(function () {
                        showAlert(region);
                    }, delay * 1000, 1);    
                }                            
            }
        });
           

})()
