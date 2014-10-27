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
            this.getActivityFeedSetting = function () {
                $http.get('app/streamboard/get_activity_feed_setting').success(function (data) {
                    Service.showSubscriber = data.showSubscriber;
                    Service.showFollower = data.showFollower;
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

            this.getActivityFeedSetting();


            function requestStreamData() {

                $http.get('app/streamboard/get_stream_data').success(function (data) {
                    /*we should get data in "date ASC" order because we first should notifications which occur early*/
                    for (var key in data) {
                        var notification = data[key];
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
                });
            }

            $interval(function(){
                requestStreamData();
            }, 3000);
            this.testData = function (type, number, regionNumber) {
                if (!number) {
                    number = 1;
                }
                var message;

                switch (type) {
                    case 'donations':
                        message = 'just donated to ...';
                        break;
                    case 'followers':
                        message = 'just followed your channel';
                        break;
                    case 'subscribers':
                        message = 'just subscribed to your channel';
                        break;
                    case 'all':
                        this.testData('donations', number, regionNumber);
                        this.testData('followers', number, regionNumber);
                        this.testData('subscribers', number, regionNumber);
                        return;
                    default:
                        throw new Exception('testData wrong type');
                }

                for (var i = 0; i < number; i++) {
                    var notification = {"id": -1, "type": "donations", "message": "Testaccount" + (i + 1) + ' ' + message, "date": Math.round(new Date().getTime() / 1000)};
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

        });
})();
