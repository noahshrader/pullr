var PullrStatistic = {};
PullrStatistic.MAIN_URL = "//<?=$_SERVER['HTTP_HOST']?><?= \Yii::$app->urlManager->baseUrl?>/";
PullrStatistic.ANGULAR_LIB_URL = PullrStatistic.MAIN_URL + 'public/angular-1.3.4.min.js';
PullrStatistic.API_URL = PullrStatistic.MAIN_URL + 'app/statistic';

PullrStatistic.LAYOUT_TYPE_SINGLE = "<? echo common\models\Campaign::LAYOUT_TYPE_SINGLE; ?>";
PullrStatistic.LAYOUT_TYPE_TEAM = "<? echo common\models\Campaign::LAYOUT_TYPE_TEAM; ?>";
PullrStatistic.LAYOUT_TYPE_MULTI = "<? echo common\models\Campaign::LAYOUT_TYPE_MULTI; ?>";
PullrStatistic.onCampaignTemplateLoaded = PullrStatistic.onCampaignTemplateLoaded || function() {};
PullrStatistic.bootstrapApp = function(){
    angular.element(document).ready(function() {
        angular.bootstrap(document, ['PullrStatisticApp']);
    });
}

PullrStatistic.loadStatsticApp = function() {
    var html = document.getElementsByTagName('html')[0];
    html.dataset.ngApp = 'PullrStatisticApp';

    var app = angular.module('PullrStatisticApp', []);

    app.controller('PullrStatisticCtrl', function($scope, PullrStatisticData) {
        $scope.statisticService = PullrStatisticData;
        $scope.$watch('statisticService.statisticData', function(){
            $scope.statistic = $scope.statisticService.statisticData;
        });
    });

    app.service('PullrStatisticData', function($http) {
        var Service = {};
        Service.statisticData = null;
        Service.getStatistic = function() {
            $http.get(PullrStatistic.API_URL + '/amount_raised').success(function(data) {
                Service.statisticData = data;
            });
        }

        Service.init = function() {
            Service.getStatistic();
        }

        Service.init();
        return Service;
    });

    app.controller('PullrCharityCampaignCtrl', function($scope, PullrCharityCampaignData) {
        $scope.charityCampaignService = PullrCharityCampaignData;
        $scope.$watch('charityCampaignService.charityCampaignData', function() {
            $scope.charityCampaignData = $scope.charityCampaignService.charityCampaignData
        });


    });

    app.service('PullrCharityCampaignData', function($http) {
        var Service = {};
        Service.charityCampaignData = [];
        Service.getCharityCampaignData = function() {
            $http.get(PullrStatistic.API_URL + '/charity_campaign_data').success(function(data) {
                Service.charityCampaignData = data;
            });
        }
    });


}

PullrStatistic.loadFeaturedCampaignApp = function(){

    var app = angular.module('PullrStatisticApp');

    app.controller('PullrCampaignCtrl', function($scope, $timeout, PullrCampaignData) {
        $scope.LAYOUT_TYPE_SINGLE = PullrStatistic.LAYOUT_TYPE_SINGLE;
        $scope.LAYOUT_TYPE_MULTI = PullrStatistic.LAYOUT_TYPE_MULTI;
        $scope.LAYOUT_TYPE_TEAM = PullrStatistic.LAYOUT_TYPE_TEAM;

        $scope.selectedCampaign = null;
        $scope.isDataReady = false;
        $scope.campaignDataService = PullrCampaignData;

        $scope.campaignDataService.fetchCampaignData(function() {
            $scope.campaigns = $scope.campaignDataService.campaignData;
            console.log($scope.campaigns);
            if ( ! $scope.isDataReady ) {
                $scope.isDataReady = true;
                $timeout(function() {
                    PullrStatistic.onCampaignTemplateLoaded();
                }, 100);

            }
        });

        $scope.showStreamWindow = false;

        $scope.$watch('campaignDataService.campaignData', function() {
            $scope.campaigns = $scope.campaignDataService.campaignData;
        });

        $scope.showChannelInfo = function(channel) {
            console.log('showChannel');
            channel.displayInfo = true;

        }

        $scope.hideChannelInfo = function(channel) {
            channel.displayInfo = false;
        }

        $scope.watchChannel = function(campaign, channel) {
            $scope.selectedCampaign = campaign;
            $scope.selectedChannel = channel;
            $scope.showStreamWindow = true;
            $scope.embedPlayerUrl = '//www.twitch.tv/widgets/live_embed_player.swf?channel=' + channel.name;
            $scope.hostname = 'hostname=www.twitch.tv&channel=' + channel.name + '&auto_play=true&start_volume=25';
            $scope.chatUrl = '//twitch.tv/' + channel.name + '/chat?popout=';
            $timeout(function() {
                var watchWindowTop = angular.element('#watch_window').offset().top;
                angular.element("html, body").animate({ scrollTop: top }, 600);
            }, 50);

            if (channel.hasOwnProperty('logo')) {
                $scope.selectedLogo = channel.logo;
            } else if (channel.hasOwnProperty('image')) {
                $scope.selectedLogo = channel.image.size300;
            }
        }

    });

    app.service('PullrCampaignData', function($http) {
        var Service = this;
        Service.campaignData = [];
        Service.fetchCampaignData = function(successCb) {
            successCb = successCb || function(){};
            $http.post(PullrStatistic.API_URL + '/campaign_data').success(function(data) {
                Service.campaignData = data;
                successCb();
            });
        }
        return Service;
    });

    app.filter('trusted', ['$sce', function ($sce) {
        return function(url) {
            return $sce.trustAsResourceUrl(url);
        };
    }]);
}
PullrStatistic.init = function() {
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = PullrStatistic.ANGULAR_LIB_URL;
    script.async = true;
    script.onload = function(){
        PullrStatistic.loadStatsticApp();
        PullrStatistic.loadFeaturedCampaignApp();
        PullrStatistic.bootstrapApp();
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}

PullrStatistic.init();
