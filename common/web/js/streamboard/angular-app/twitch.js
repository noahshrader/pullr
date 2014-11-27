angular.module('pullr.streamboard.twitch', []).factory('twitch', ['$http', '$interval', '$q', 'stream',
	function($http, $interval, $q, stream){
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
		return $http.get('app/streamboard/get_followers');
        
	}

	service.getSubscribers = function() {	
		if (Pullr.user.userFields.twitchPartner == 1) {
			return $http.get('app/streamboard/get_subscribers');
		}       	
	}

	service.init();
	return service;


}])
.factory('twitchNotification', ['twitch', '$interval', 'stream', '$timeout', function(twitch, $interval, stream, $timeout) {
	var service = {};
	service.interval = 10000;
	service.lastRequestTime = null;
	service.getCurrentUTCDate = function(offset) {		
		offset = offset || null;
		var now = new Date();				
		var result = new Date();
		if (offset != null) {
			elapse = now.getTime() - offset.getTime();
			result = new Date(now.getTime() - elapse);
		} 		
		return result;
	}

	service.lastRequestTime = service.getCurrentUTCDate();
	service.lastMaxCreatedAt = service.getCurrentUTCDate();
	
	service.filterList = function(list) {		

		var result = [];
		angular.forEach(list, function(item) {
			var createdAt = new Date(item.created_at);		
			if (createdAt >= service.lastRequestTime) {								
				result.push(item);
			}
		});
		return result;
	}

	service.requestTwitchData = function() {
		var offset = new Date();
		twitch.getFollowers().then(function(response) {			
			var follows = service.filterList(response.data.follows);			
			if (follows.length > 0) {
				stream.pushFollowerAlerts(follows);
			}			
			var promise = twitch.getSubscribers();
			if (promise) {
				promise.then(function(response) {				
					var subscriptions = service.filterList(response.data.subscriptions);				
					service.lastRequestTime = service.getCurrentUTCDate(offset);
					if (subscriptions.length > 0) {
						stream.pushSubscriberAlerts(subscriptions);
					}					
					$timeout(function() {
						service.requestTwitchData();
					}, service.interval);
				});
			} else {
				service.lastRequestTime = service.getCurrentUTCDate(offset);
				$timeout(function() {
					service.requestTwitchData();
				}, service.interval);
			}
		});		
	}

	service.init = function(){
		service.requestTwitchData();
	}	

	service.init();
	return service;
}]);