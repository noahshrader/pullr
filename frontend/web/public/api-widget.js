var html = document.getElementsByTagName('html')[0];
html.dataset.ngApp = 'PullrApp';

var body = document.getElementsByTagName('body')[0];
body.dataset.ngController = 'PullrCtrl';

var head = document.getElementsByTagName('head')[0];
head.dataset.ngController = 'PullrCtrl';
var app = angular.module('PullrApp', []);

app.controller('PullrCtrl', function ($scope, $interval, CampaignDataService) {
	$scope.isDataReady = false;
	CampaignDataService.loadCampaign(function(data) {
		$scope.campaign = data;	
		
		CampaignDataService.loadChannels(function(data) {
			if ($scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
				$scope.channel = data;
				$scope.selectedChannel = $scope.campaign.channelName;
			} else {
				$scope.channels = data;
				if (data.length > 0) {
					$scope.selectedChannel = data[0].name;
				}
				
			}
			$scope.isDataReady = true;
		});
		
	});

	$interval(function(){
		CampaignDataService.loadChannels(function(data) {
			if ($scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
				$scope.channel = data;
			} else {
				$scope.channels = data;
			}
			$scope.isDataReady = true;
		});
	}, 30000);
});

app.factory('CampaignDataService', function($http) {
	var service = {};
	service.data = {};
	service.campaign = null;
	service.user = null;
	service.channels = null;
	service.call = function(method, params, callback) {
		if (!params) {
	        params = {};
	    }
	    
	    for (var attrname in Pullr.requestParams) { 
	        params[attrname] = Pullr.requestParams[attrname]; 
	    }

	    var url = Pullr.API_URL + method;

	    $http.post(url,params).then(function(response) {
	    	callback(response.data);
	    });
	}

	service.loadCampaign = function(callback) {		
		service.call('campaign', {}, function(data) {
			service.campaign = data;
			callback(data);
		});
	}

	service.loadChannels = function(callback) {
		service.call('channels', {}, function(data) {
			service.channels = data;
			callback(data);
		});	
	}

	return service;
});


app.directive('pullrCampaignName', function(CampaignDataService) {
	return {
		restrict: 'A',
		scope: '@',
		template: '<span ng-cloak>{{campaign.name}}</span>',
		link: function(scope,element, attr) {
		}
	}
});

app.directive('pullrCampaignLayout', function($interval, $timeout, CampaignDataService) {
	return {
		restrict: 'A',
		scope: '@',
		link: function(scope, element, attr) {
			scope.$watch('selectedChannel', function(selectedChannel) {
				if (selectedChannel != null) {
					scope.embedPlayerUrl = 'http://www.twitch.tv/widgets/live_embed_player.swf?channel=' + scope.selectedChannel;
					scope.hostname = 'hostname=www.twitch.tv&channel=' + scope.selectedChannel + '&auto_play=true&start_volume=25';
					scope.chatUrl = 'http://twitch.tv/' + scope.selectedChannel + '/chat?popout=';
				}
			});
			scope.setChannel = function(channelName) {
				scope.selectedChannel = channelName;
			}
			scope.getLayoutUrl = function() {
				if (typeof(scope.campaign) != 'undefined') {
					if (scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
						return Pullr.API_URL + 'campaignsinglestreamlayout';
					} else if(scope.campaign.layoutType == Pullr.LAYOUT_TYPE_MULTI) {
						return Pullr.API_URL + 'campaignmultistreamlayout';
					} else {
						return Pullr.API_URL + 'campaignteamstreamlayout'; 
					}	
				}				
			}

			scope.afterLoadTemplate = function(){
				console.log('Campaign template loaded');
				myCustomAfterRender = myCustomAfterRender || function(){};
				myCustomAfterRender();
			}
		},
		template:'<div ng-include="getLayoutUrl() | trusted" onload="afterLoadTemplate()" ng-cloak></div>' 
	}
});

app.filter('trusted', ['$sce', function ($sce) {
    return function(url) {
        return $sce.trustAsResourceUrl(url);
    };
}]);

angular.element(document).ready(function() {
  	angular.bootstrap(document, ['PullrApp']);
});