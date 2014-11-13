(function () {
    var app = angular.module('pullr.streamboard.stream', ['pullr.streamboard.regions']).
        service('stream', function ($http, regions, $interval) {
            var Service = this;
            /*for each of regions we have separate stream*/
            this.streams = [];
            this.streams[1] = [];
            this.streams[2] = [];
            /*list of events we already have viewed*/
            this.alreadyViewed = [];


            this.showSubscriber = true;
            this.showFollower = true;
            this.groupUser = false;

            this.getActivityFeedSetting = function () {
                $http.get('app/streamboard/get_activity_feed_setting').success(function (data) {
                    Service.showSubscriber = data.showSubscriber;
                    Service.showFollower = data.showFollower;
                    Service.groupUser = data.groupUser;
                });
            }

            this.toggleSubscriber = function () {
                $http.post('app/streamboard/set_activity_feed_setting', {
                    showSubscriber: Service.showSubscriber
                });
            }

            this.toggleFollower = function () {
                $http.post('app/streamboard/set_activity_feed_setting', {
                    showFollower: Service.showFollower
                });
            }

            this.toggleGroupUser = function() {
                $http.post('app/streamboard/set_activity_feed_setting', {
                    groupUser: Service.groupUser
                });
            }

            this.getActivityFeedSetting();


            this.requestStreamData = function() {

                $http.get('app/streamboard/get_stream_data').success(function (data) {
                    /*we should get data in "date ASC" order because we first should notifications which occur early*/
                    for (var key in data) {
                        var notification = data[key];
                        Service.pushNotification(notification);
                    }
                });
            }

            this.pushNotification = function(notification){
                var notification = notification;
                var id = notification.type + '_' + notification.id;
                /*check if we already view it*/
                if (!(id in Service.alreadyViewed)) {
                    console.log('[STREAM_LOG]');
                    console.log(notification);
                    Service.alreadyViewed[id] = true;
                    Service.streams[1].push(notification);
                    if (regions.regions.length > 1) {
                        /*if we have second region*/
                        Service.streams[2].push(notification);
                    }
                }
            }

            this.pushFollowerAlerts = function(list) {
                angular.forEach(list, function(item) {                       
                    var notification = {
                        id: item.user._id,
                        type:'followers',
                        message: sprintf('%s just followed your channel!', item.user.name),
                        follow: item,
                        date: new Date(item.created_at)
                    }
                    Service.pushNotification(notification);
                });
            }

            this.pushSubscriberAlerts = function(list) {
                angular.forEach(list, function(item) {                       
                    var notification = {
                        id: item.user._id,
                        type:'subscribers',
                        message: sprintf('%s just subscribed your channel!', item.user.name),
                        follow: item,
                        date: new Date(item.created_at)
                    }
                    Service.pushNotification(notification);
                });
            }

            $interval(function(){
                Service.requestStreamData();
            }, 5000);
            this.testData = function (type, number, region) {
                number = number||1;
                var message;
                var regionNumber = region.regionNumber;
                switch (type) {
                    case 'donations':
                        message = region.widgetAlerts.donationsPreference.alertText||'just donated to ...';
                        break;
                    case 'followers':
                        message = region.widgetAlerts.followersPreference.alertText||'just followed your channel';
                        break;
                    case 'subscribers':
                        message = region.widgetAlerts.subscribersPreference.alertText||'just subscribed to your channel';
                        break;
                    case 'campaign_donations':
                        if(region.widgetCampaignBar.alertsModule.includeDonations !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.donationText||'just donated to ...';
                        type = 'donations';
                        break;
                    case 'campaign_followers':
                        if(region.widgetCampaignBar.alertsModule.includeFollowers !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.followerText||'just followed your channel';
                        type = 'followers';
                        break;
                    case 'campaign_subscribers':
                        if(region.widgetCampaignBar.alertsModule.includeSubscribers !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.subscriberText||'just subscribed to your channel';
                        type = 'subscribers';
                        break;
                    default:
                        throw new Exception('testData wrong type');
                }

                
                for (var i = 0; i < number; i++) {
                    var notification = {"id": -1, "type": "donations", "message": message, "date": Math.round(new Date().getTime() / 1000)};
                    notification.type = type;
                    if (regionNumber) {
                        Service.streams[regionNumber].push(notification);
                    } else {
                        /**in other case let's push notification for both if region*/
                        Service.streams[1].push(notification);
                        if (regions.regions.length > 1) {
                            /*if we have second region*/
                            Service.streams[2].push(notification);
                        }
                    }
                }
            }

            this.testCampaignAlert = function(region){
                this.testData('campaign_followers', 1, region);
                this.testData('campaign_subscribers', 1, region);
                this.testData('campaign_donations', 1, region);
            }
        });
})();
