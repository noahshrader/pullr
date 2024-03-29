(function () {
    var app = angular.module('pullr.streamboard.regionsPanels', [
            'pullr.streamboard.stream',
            'pullr.streamboard.regions',
            'pullr.streamboard.alertMediaManager',
            'pullr.streamboard.donations',
            'pullr.streamboard.campaigns',
            'pullr.currentTime',
            'pullr.countUpTimer',
            'timer',
            'simpleMarquee',
            'pullr.streamboard.twitch',
            'pullr.streamboard.config',
            'pullr.streamboard.activityFeed'
        ]).
        controller('RegionsCtrl', ['$sce', '$http', '$scope', 'stream', 'regions', '$interval', '$timeout', 'alertMediaManager', 'donations', 'campaigns', 'simpleMarqueeHelper', 'streamboardConfig', 'twitchNotification',
            function ($sce, $http, $scope, stream, regions, $interval, $timeout, alertMediaManager, donations, campaigns, simpleMarqueeHelper, streamboardConfig, twitchNotification) {
                $scope.streamService = stream;
                $scope.regionsService = regions;
                $scope.donationsService = donations;
                $scope.campaignsService = campaigns;
                $scope.scroll = true;
                $scope.duration = 1500;
                $scope.alertMediaManagerService = alertMediaManager;
                $scope.streamboardConfig = streamboardConfig;

                if (Pullr.Streamboard != undefined && Pullr.Streamboard.region != undefined) {
                    $scope.region = Pullr.Streamboard.region;
                    function requestRegion() {
                        $http.get('app/streamboard/get_regions_ajax').success(function (data) {
                            var newRegion = null;
                            if (data[$scope.region.regionNumber - 1]) {
                                var newRegion = data[$scope.region.regionNumber - 1];
                                //keep alert when update region
                                if ($scope.region.toShow) {
                                    var toShow = $.extend({}, $scope.region.toShow);
                                    newRegion['toShow'] = $scope.region.toShow;
                                }
                                $scope.region = newRegion;
                            }

                            $timeout(function() {
                                requestRegion();
                            }, 10000)

                        });
                    }
                   requestRegion();
                }

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

                if ( typeof(Pullr.Streamboard.regionNumber) == 'undefined') {
                    $scope.$watch('streamboardConfig.config.region2HeightPercent', function(height) {
                        if (height > 0) {
                            var $region2 = $(".regionsContainer .region:last-child");
                            $region2.height((height) + '%');
                            recalculateRegionSize();
                        }
                    });
                }


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
                    var alertType = $(ui.element).attr("alert-type");
                    var preferenceName = '';
                    switch(alertType){
                        case 'donations':
                            preferenceName = 'donationsPreference';
                            break;
                        case 'followers':
                            preferenceName =  'followersPreference';
                            break;
                        case 'subscribers':
                            preferenceName =  'subscribersPreference';
                            break;
                        default:
                            break
                    }
                    region.widgetAlerts[preferenceName].imageWidth = ui.size.width;
                    region.widgetAlerts[preferenceName].imageHeight = ui.size.height;
                    region.widgetAlerts[preferenceName].imagePositionX = ui.position.left;
                    region.widgetAlerts[preferenceName].imagePositionY = ui.position.top;
                    regions.regionChanged(region);
                }

                $scope.onResizeAlertMessage = function(region, event, ui) {
                    var alertType = $(ui.element).attr("alert-type");
                    var preferenceName = '';
                    switch(alertType){
                        case 'donations':
                            preferenceName = 'donationsPreference';
                            break;
                        case 'followers':
                            preferenceName =  'followersPreference';
                            break;
                        case 'subscribers':
                            preferenceName =  'subscribersPreference';
                            break;
                        default:
                            break
                    }
                    region.widgetAlerts[preferenceName].messageWidth = ui.size.width;
                    region.widgetAlerts[preferenceName].messageHeight = ui.size.height;
                    region.widgetAlerts[preferenceName].messagePositionX = ui.position.left;
                    region.widgetAlerts[preferenceName].messagePositionY = ui.position.top;
                    regions.regionChanged(region);
                }




                $scope.onResizeTag = function(region, event, ui){
                    var tagType = $(ui.element).attr("tag-type");
                    var tagPreferenceName = '';
                    switch(tagType){
                        case 'largestdonation':
                            tagPreferenceName = 'largestDonationWidget';
                            break;
                        case 'lastdonoranddonation':
                            tagPreferenceName =  'lastDonorAndDonationWidget';
                            break;
                        case 'lastdonor':
                            tagPreferenceName =  'lastDonorWidget';
                            break;
                        case 'lastfollower':
                            tagPreferenceName = 'lastFollowerWidget';
                            break;
                        case 'lastsubscriber':
                            tagPreferenceName =  'lastSubscriberWidget';
                            break;
                        case 'topdonor':
                            tagPreferenceName =  'topDonorWidget';
                            break;
                        default:
                            break
                    }
                    region.widgetTags[tagPreferenceName].width = ui.size.width;
                    region.widgetTags[tagPreferenceName].height = ui.size.height;
                    regions.regionChanged(region);
                }


                $scope.onResizeCampaignBarMessage = function(region, event, ui) {
                    region.widgetCampaignBar.messagesModule.messageWidth = ui.size.width;
                    region.widgetCampaignBar.messagesModule.messageHeight = ui.size.height;
                    regions.regionChanged(region);
                }

                $scope.onResizeCampaignBarAlert = function(region, event, ui) {
                    region.widgetCampaignBar.alertsModule.messageWidth = ui.size.width;
                    region.widgetCampaignBar.alertsModule.messageHeight = ui.size.height;
                    regions.regionChanged(region);
                }

                $scope.getRegionSelector = function(region) {
                    return '#region-' + region.regionNumber;
                }

                $scope.formatMsgHtml = function(content) {
                    return $sce.trustAsHtml(content);
                }

                $scope.getContainmentByRegion = function(region) {
                    return '#region-' + region.regionNumber;
                }

                $scope.getCampaignBarSelector = function(region) {
                    return '#region-' + region.regionNumber + ' #campaign-bar';
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
                    if ($scope.region) {
                        $interval(function() {
                            $scope.region.toShow = {alert: {
                                animationDirectionArray:[],
                                isRunning: false
                            }};
                            $interval(function() {
                                showAlert($scope.region);
                            }, 1, 1);
                        }, 4000, 1);

                    } else {
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
                    }
                });

                $interval(function () {
                    $scope.currentTimestamp = new Date().getTime();
                }, 1000);

                function updateTimestamps() {
                    if (Pullr.Streamboard.region) {
                        updateRegionTimestamps(Pullr.Streamboard.region);
                    } else {
                        for (var key in regions.regions) {
                            var region = regions.regions[key];
                            updateRegionTimestamps(region);
                        }
                    }

                }

                function updateRegionTimestamps(region){
                    var module = region.widgetCampaignBar.timerModule;
                    module.countDownFromTimestamp = new Date(module.countDownFrom).getTime();
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
                        window.requireGoogleFont(region.widgetTags.fontStyle);

                    }
                }

                function capitaliseFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }

                function showAlert(region) {
                    var stream = null;
                    if (Pullr.Streamboard.region) {
                        stream = $scope.streamService.streams[1];
                    } else {
                        stream = $scope.streamService.streams[region.regionNumber];
                    }

                    var notification = false;

                    if (region.widgetType == 'widget_alerts' || (region.widgetType == 'widget_campaign_bar' && region.widgetCampaignBar.alertsEnable)) {
                        while (stream.length > 0 && notification == false) {
                            console.log(stream);
                            notification = stream.shift();
                            console.log(notification);
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
                        stream = [];
                    }

                    if (notification) {

                        console.log(['WE HAVE NOTIFICATION FOR REGION ' + region.regionNumber]);
                        console.log(notification);

                        var toShow = region.toShow.alert;
                        toShow.animationDirection = '';
                        toShow.isRunning = true;
                        toShow.message = notification.message;
                        toShow.type = notification.type;

                        if (region.widgetType == 'widget_alerts') {

                            toShow.preference = region.widgetAlerts[notification.type + 'Preference'];
                            toShow.notificationType = notification.type;
                            var preference = toShow.preference;
                            toShow.image = alertMediaManager.getImageUrl(preference.image, preference.imageType);
                            if(notification.soundFile){
                                var soundFile = notification.soundFile;
                                var fileType = null;
                            }else{
                                var soundFile = preference.sound;
                                var fileType = preference.soundType;
                            }
                            alertMediaManager.playSound(soundFile, fileType, preference.volume);
                            if (preference.animationDirection) {
                                toShow.animationDirectionArray = preference.animationDirection.split(',');
                                var widget = $('#region-' + region.regionNumber + ' .widget-alerts.' + preference.preferenceType);
                                widget.off(animationEndEvent);
                                if (toShow.animationDirectionArray.length > 1) {
                                    toShow.animationDirection = 'animated ' + toShow.animationDirectionArray[0];
                                }

                                widget.one(animationEndEvent, function(){

                                    $interval(function () {
                                        hideAlert(region);
                                    }, preference.animationDuration * 1000, 1);
                                });

                            } else {
                                $interval(function () {
                                    hideAlert(region);
                                }, preference.animationDuration * 1000, 1);
                            }




                            return;
                        } else {
                            /**so we have campaign bar*/
                            var alertsModule = region.widgetCampaignBar.alertsModule;
                            toShow.background = alertsModule.background;
                            toShow.image = '';

                            if(notification.soundFile){
                                var soundFile = notification.soundFile;
                                var fileType = null;
                            }else{
                                var soundFile = alertsModule.sound;
                                var fileType = alertsModule.soundType;
                            }

                            alertMediaManager.playSound(soundFile, fileType, alertsModule.volume);

                            if (alertsModule.animationDirection) {
                                toShow.animationDirectionArray = alertsModule.animationDirection.split(',');
                                var widget = $('#region-' + region.regionNumber + ' .bar-alert:eq(0)');
                                widget.off(animationEndEvent);
                                if(toShow.animationDirectionArray.length > 1){
                                    toShow.animationDirection = 'animated ' + toShow.animationDirectionArray[0];
                                }

                                widget.one(animationEndEvent, function(){
                                    $interval(function () {
                                        hideAlert(region);
                                    }, alertsModule.animationDuration * 1000, 1);
                                });


                            } else {
                                $interval(function () {
                                    hideAlert(region);
                                }, alertsModule.animationDuration * 1000, 1);
                            }


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
                        if (region.widgetType == 'widget_alerts') {
                            $('#region-' + region.regionNumber + ' .widget-alerts.' + region.toShow.alert.preference.preferenceType).one(animationEndEvent, function(){
                                region.toShow.alert.message = null;
                                region.toShow.alert.animationDirectionArray = [];
                                region.toShow.alert.background = '';
                                $interval(function () {
                                    showAlert(region);
                                }, delay * 1000, 1);
                            });
                        } else {
                            $('#region-' + region.regionNumber + ' .bar-alert:eq(0)').one(animationEndEvent, function(){
                                console.log('hide alert');
                                region.toShow.alert.message = null;
                                region.toShow.alert.animationDirectionArray = [];
                                region.toShow.alert.background = '';
                                $interval(function () {
                                    showAlert(region);
                                }, delay * 1000, 1);
                            });
                        }
                    } else {
                        region.toShow.alert.animationDirection = '';
                        region.toShow.alert.message = null;

                        isShowingNotification = false;
                        $interval(function () {
                            showAlert(region);
                        }, delay * 1000, 1);
                    }
                }

            }]);


})()
