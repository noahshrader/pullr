var html = document.getElementsByTagName('html')[0];
html.dataset.ngApp = 'PullrApp';

var body = document.getElementsByTagName('body')[0];
body.dataset.ngController = 'PullrCtrl';

var app = angular.module('PullrApp', ['ngSanitize']);

app.controller('PullrCtrl', function ($scope, $rootScope, $interval, CampaignDataService, $sce) {
    $scope.isDataReady = false;
    $scope.LAYOUT_TYPE_SINGLE = Pullr.LAYOUT_TYPE_SINGLE;
    $scope.LAYOUT_TYPE_MULTI = Pullr.LAYOUT_TYPE_MULTI;
    $scope.LAYOUT_TYPE_TEAM = Pullr.LAYOUT_TYPE_TEAM;

    if ( false == $scope.isDataReady) {
    	CampaignDataService.loadCampaign().success(function(data) {
    		$rootScope.campaign = data;
	        $scope.campaign = data;
	        // $scope.campaign.description = $sce.get

	        CampaignDataService.loadChannels().success(function(data) {

	            if ($scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
	                $scope.channel = data;
	                $scope.selectedChannel = data;
	            } else {
	                $scope.channels = data;
	                if (data.length > 0) {
	                    $scope.selectedChannel = data[0];
	                }

	            }
	            $scope.isDataReady = true;
	        }).error(function(data) {
	        	alert('Please enter a valid channel name');
	        });

	        if ($scope.campaign.layoutType == Pullr.LAYOUT_TYPE_TEAM) {
	            CampaignDataService.loadTeam()
	            	.success(function(data) {
		                $scope.team = data;
		            })
		            .error(function() {
		            	alert('Can\'t load team')
		            });
	        }

	    });
    }


    $interval(function(){
        CampaignDataService.loadChannels().success(function(data) {
            if ($scope.campaign.layoutType == Pullr.LAYOUT_TYPE_SINGLE) {
                $scope.channel = data;
            } else {
                $scope.channels = data;
            }
            $scope.isDataReady = true;
        });
    }, 90000);

    $interval(function(){
        CampaignDataService.loadCampaign().success(function(data){
            $scope.campaign = data;
        });
    }, 90000);

    $scope.to_trusted = function(html_code) {
        return $sce.trustAsHtml(html_code);
    }
});

app.factory('CampaignDataService', function($http) {
    var service = {};
    service.data = {};
    service.campaign = null;
    service.user = null;
    service.channels = null;
    service.call = function(method, params) {
        if (!params) {
            params = {};
        }

        for (var attrname in Pullr.requestParams) {
            params[attrname] = Pullr.requestParams[attrname];
        }

        var url = Pullr.API_URL + method;

        return $http.post(url,params);
    }

    service.loadCampaign = function(callback) {
        return service.call('campaign', {});
    }

    service.loadChannels = function(callback) {
        return service.call('channels', {});
    }

    service.loadTeam = function(callback) {
        return service.call('team');
    }
    return service;
});

app.directive('bgImage', function(){
    return function(scope, element, attrs){
        attrs.$observe('bgImage', function(value) {
            element.css({
                'background-image': 'url(' + value +')',
                'background-size' : 'cover'
            });
        });
    };
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
        link : function(scope, element, attr) {

            scope.$watch('selectedChannel', function(selectedChannel) {
                if (selectedChannel != null) {
                    scope.embedPlayerUrl = '//www.twitch.tv/widgets/live_embed_player.swf?channel=' + scope.selectedChannel.name;
                    scope.hostname = 'hostname=www.twitch.tv&channel=' + scope.selectedChannel.name + '&auto_play=true&start_volume=25';
                    scope.chatUrl = '//twitch.tv/' + scope.selectedChannel.name + '/chat?popout=';
                }
            });

            scope.setChannel = function(channel) {
                scope.selectedChannel = channel;
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

app.directive('pullrDonorList', function(CampaignDataService) {
    return {
        restrict : 'A',
        scope : '@',
        link : function(scope, element, attr) {
            $('#btnShowDonorList').magnificPopup({
                type: 'inline',
                preloader: false,
                modal: false,
                mainClass: 'mfp-fade'
            });
            scope.closeDonorModal = function() {
                $.magnificPopup.close();
            }
        },
        templateUrl: Pullr.API_URL + 'donorlist'
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
