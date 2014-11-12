angular.module('pullr.streamboard.twitch', []).factory('twitch', function($http, $interval, $q, stream){
	var service = {};
	service.isReady = false;
	service.channelName = null;
	service.clientId = null;
	service.lastRequestTime = new Date();
	service.token = null;
	service.init = function() {
		if (window.Twitch) {
			service.channelName = Pullr.user.userFields.twitchChannel;
        	service.clientId = Pullr.twitchClientId;
        	Twitch.init({clientId: service.clientId}, function (error, status) {
        		if ( ! error ) {
        			service.isReady = true;
        		}
        	});
		}
	}

	service.getFollowers = function() {
		var deferred = $q.defer();
		var method = 'channels/' + service.channelName + '/follows';
        Twitch.api({method: method, params: {limit: 100} }, function (error, list) {
            if ( ! error ) {
            	deferred.resolve(list);
            }
        });
        return deferred.promise;
	}

	service.getSubscribers = function() {
		var deferred = $q.defer();
		if (Pullr.user.userFields.twitchPartner == 1) {
			return $http.get('app/streamboard/get_subscribers');
		}
       	return deferred.promise;
	}

	

	service.init();
	return service;


})
.factory('twitchNotification', function(twitch, $interval, stream) {
	var service = {};
	service.interval = 30000;
	
	service.getCurrentUTCDate = function() {
		var now = new Date();
		//we really want to decrease time by 15 secs, 
		//because there are some delay from the time user click on follow/subscribe button and the time twitch record their action.		
		var t = now.getTime() - 15000;
		return new Date(t)
	}

	service.lastRequestTime = service.getCurrentUTCDate();
	service.lastMaxCreatedAt = service.getCurrentUTCDate();
	console.log(service.lastRequestTime);
	service.filterList = function(list) {		
		var result = [];
		angular.forEach(list, function(item) {
			var createdAt = new Date(item.created_at);
			if (createdAt >= service.lastRequestTime) {				
				if (createdAt > service.lastMaxCreatedAt) {
					service.lastMaxCreatedAt = createdAt;
				}
				result.push(item);
			} else {
				return false;
			}
		});
		return result;
	}

	service.requestTwitchData = function() {
		twitch.getFollowers().then(function(response) {
			var follows = service.filterList(response.follows);
			if (follows.length > 0) {
				stream.pushFollowerAlerts(follows);
			}
			twitch.getSubscribers().then(function(response) {				
				var subscriptions = service.filterList(response.data.subscriptions);				
				service.lastRequestTime = service.getCurrentUTCDate();
				if (subscriptions.length > 0) {
					stream.pushSubscriberAlerts(subscriptions);
				}
			});
		});		
	}
	
	return service;
});