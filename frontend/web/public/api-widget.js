Pullr.LAYOUT_TYPE_SINGLE = 'Single Stream';
Pullr.LAYOUT_TYPE_TEAM = 'Team Stream';
Pullr.LAYOUT_TYPE_MULTI = 'Multi Stream';



var html = document.getElementsByTagName('html')[0];
html.dataset.ngApp = 'PullrApp';

var body = document.getElementsByTagName('body')[0];
body.dataset.ngController = 'PullrCtrl';

var app = angular.module('PullrApp', []);

app.controller('PullrCtrl', function ($scope) {

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
		if (service.campaign != null) {
			callback(service.campaign);
		} else {
			service.call('campaign', {}, function(data) {
				service.campaign = data;
				callback(data);
			});
		}
		
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
		template: '{{campaign.name}}',
		link: function(scope,element, attr) {
			CampaignDataService.loadCampaign(function(data) {
				scope.campaign = data;
			});
	
		}
	}
});

app.directive('pullrCampaignLayout', function($interval, CampaignDataService) {
	return {
		restrict: 'A',
		scope: '@',
		link: function(scope, element, attr) {
			scope.isDataReady = false;
			CampaignDataService.loadCampaign(function(data) {
				scope.campaign = data;	
				CampaignDataService.loadChannels(function(data) {
					if (scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
						scope.channel = data;
						scope.selectedChannel = scope.campaign.channelName;
					} else {
						scope.channels = data;
						scope.selectedChannel = data[0].name;
					}
					scope.isDataReady = true;
				});
				
				scope.$watch('selectedChannel', function() {
					scope.embedPlayerUrl = 'http://www.twitch.tv/widgets/live_embed_player.swf?channel=' + scope.selectedChannel;
					scope.hostname = 'hostname=www.twitch.tv&channel=' + scope.selectedChannel + '&auto_play=true&start_volume=25';
					scope.chatUrl = 'http://twitch.tv/' + scope.selectedChannel + '/chat?popout=';
				});
			});

			scope.setChannel = function(channelName) {
				scope.selectedChannel = channelName;
			}

			scope.getLayoutUrl = function() {
				if (typeof(scope.campaign) != 'undefined') {
					if (scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
						return Pullr.MAIN_URL + 'public/campaignSingleStreamLayout.html';
					} else {
						return Pullr.MAIN_URL + 'public/campaignMultiStreamLayout.html';
					}	
				}
				
			}

			$interval(function(){
				CampaignDataService.loadChannels(function(data) {
					if (scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
						scope.channel = data;
					} else {
						scope.channels = data;
					}
					scope.isDataReady = true;
				});
			}, 30000);
		},
		template:'<div ng-include="getLayoutUrl()"></div>' 

	}
});

app.filter('trusted', ['$sce', function ($sce) {
    return function(url) {
        return $sce.trustAsResourceUrl(url);
    };
}]);



