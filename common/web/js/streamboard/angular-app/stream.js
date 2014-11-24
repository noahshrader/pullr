(function () {
    var app = angular.module('pullr.streamboard.stream', ['pullr.streamboard.regions'])

        .constant('defaultMessage',{
            donations:'[[DonorName]] donated $[[DonorAmount]] to [[CampaignName]]!',
            followers:'[[FollowerName]] just followed your channel!',
            subscribers:'[[SubscriberName]] just subscribed your channel!'
        })

        .service('stream', function ($http, regions, $interval, $interpolate, defaultMessage, campaigns, customDonationSound) {
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


            this.getMessage = function(type, data, region){
                data = angular.extend({'[[DonorName]]':'', '[[DonorAmount]]':'', '[[CampaignName]]':'', '[[FollowerName]]':'', '[[SubscriberName]]':''}, data);
                var message = "";
                if(region.widgetType == "widget_campaign_bar"){
                    if(type == 'donations'){
                        message = region.widgetCampaignBar.alertsModule.donationText||defaultMessage.donations;
                    }
                    if(type == 'followers'){
                        message = region.widgetCampaignBar.alertsModule.followerText||defaultMessage.followers;
                    }
                    if(type == 'subscribers'){
                        message = region.widgetCampaignBar.alertsModule.subscriberText||defaultMessage.subscribers;
                    }
                }else{
                    if(type == 'donations'){
                        message = region.widgetAlerts.donationsPreference.alertText||defaultMessage.donations;
                    }
                    if(type == 'followers'){
                        message = region.widgetAlerts.followersPreference.alertText||defaultMessage.followers;
                    }
                    if(type == 'subscribers'){
                        message = region.widgetAlerts.subscribersPreference.alertText||defaultMessage.subscribers;
                    }
                }
                message = message.replace(/\[\[/gi,'{{').replace(/\]\]/gi,'}}');
                var values =  {
                    DonorName:data['[[DonorName]]'],
                    DonorAmount:data['[[DonorAmount]]'],
                    CampaignName:data['[[CampaignName]]'],
                    FollowerName:data['[[FollowerName]]'],
                    SubscriberName:data['[[SubscriberName]]']
                };
                var template = $interpolate(message);
                message = template(values);
                return message;
            }


            this.requestStreamData = function() {
                $http.get('app/streamboard/get_stream_data').success(function (data) {
                    /*we should get data in "date ASC" order because we first should notifications which occur early*/
                    for (var key in data) {
                        var notification = data[key];
                        if(notification.type === 'donations'){
                            var alertdata = {
                                id: notification.id,
                                type:'donations',
                                data:{
                                        '[[DonorName]]':notification.donation.nameFromForm,
                                        '[[DonorAmount]]':notification.donation.amount,
                                        '[[CampaignName]]':campaigns.getcampaignName(notification.donation.campaignId)
                                   },
                                date: notification.date
                            }
                            Service.pushCustomAlert(alertdata);
                        }else{
                            Service.pushNotification(notification);
                        }
                    }
                });
            }


            this.pushCustomAlert = function(notification){
                var id = notification.type + '_' + notification.id;
                if (!(id in Service.alreadyViewed) === false){
                    return true;
                }
                Service.alreadyViewed[id] = true;
                var message = Service.getMessage(notification.type, notification.data, regions.regions[0]);
                notification.message = message;
                if(notification.type === 'donations'){
                    var soundFile = customDonationSound.getSoundFileByValue(notification.data['[[DonorAmount]]'], 1);
                    notification.soundFile = soundFile;
                    notification.soundType = null;                    
                }                

                Service.streams[1].push(notification);
                if (regions.regions.length > 1) {
                    var notificatin1 = {};
                    notificatin1.id = notification.id;
                    notificatin1.type = notification.type;
                    notificatin1.date = notification.date;
                    notificatin1.soundFile = notification.soundFile;
                    notificatin1.soundType = notification.soundType;
                    var message1 = Service.getMessage(notificatin1.type, notification.data, regions.regions[1]);
                    notificatin1.message = message1;
                    if(notificatin1.type === 'donations'){
                        var soundFile = customDonationSound.getSoundFileByValue(notification.data['[[DonorAmount]]'], 2);
                        notificatin1.soundFile = soundFile;
                        notificatin1.soundType = null;                    
                    }
                    Service.streams[2].push(notificatin1);
                }
                return true;
            }

            this.pushNotification = function(notification){
                var notification = notification;
                var id = notification.type + '_' + notification.id;
                /*check if we already view it*/
                if (!(id in Service.alreadyViewed)) {
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
                        data:{'[[FollowerName]]':item.user.name},
                        follow: item,
                        date: new Date(item.created_at)
                    }
                    Service.pushCustomAlert(notification);
                });
            }

            this.pushSubscriberAlerts = function(list) {
                angular.forEach(list, function(item) {                       
                    var notification = {
                        id: item.user._id,
                        type:'subscribers',
                        data:{'[[SubscriberName]]':item.user.name},
                        follow: item,
                        date: new Date(item.created_at)
                    }
                    Service.pushCustomAlert(notification);
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
                        message = region.widgetAlerts.donationsPreference.alertText||defaultMessage.donations;
                        break;
                    case 'followers':
                        message = region.widgetAlerts.followersPreference.alertText||defaultMessage.followers;
                        break;
                    case 'subscribers':
                        message = region.widgetAlerts.subscribersPreference.alertText||defaultMessage.subscribers;
                        break;
                    case 'campaign_donations':
                        if(region.widgetCampaignBar.alertsModule.includeDonations !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.donationText||defaultMessage.donations;
                        type = 'donations';
                        break;
                    case 'campaign_followers':
                        if(region.widgetCampaignBar.alertsModule.includeFollowers !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.followerText||defaultMessage.followers;
                        type = 'followers';
                        break;
                    case 'campaign_subscribers':
                        if(region.widgetCampaignBar.alertsModule.includeSubscribers !== true){
                            return false;
                        }
                        message = region.widgetCampaignBar.alertsModule.subscriberText||defaultMessage.subscribers;
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
