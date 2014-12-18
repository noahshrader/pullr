var PullrCampaign = {};
PullrCampaign.MAIN_URL = "//<?=$_SERVER['HTTP_HOST']?><?= \Yii::$app->urlManager->baseUrl?>/";
PullrCampaign.ANGULAR_LIB_URL = 'https://ajax.googleapis.com/ajax/libs/angularjs/1.3.7/angular.js';
PullrCampaign.API_URL = PullrCampaign.MAIN_URL + 'app/statistic';
PullrCampaign.LAYOUT_TYPE_SINGLE = "<? echo common\models\Campaign::LAYOUT_TYPE_SINGLE; ?>";
PullrCampaign.LAYOUT_TYPE_TEAM = "<? echo common\models\Campaign::LAYOUT_TYPE_TEAM; ?>";
PullrCampaign.LAYOUT_TYPE_MULTI = "<? echo common\models\Campaign::LAYOUT_TYPE_MULTI; ?>";
PullrCampaign.init = function() {
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = PullrCampaign.ANGULAR_LIB_URL;
    script.async = true;
    script.onload = function(){
        PullrCampaign.loadFeaturedCampaignApp();
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}

PullrCampaign.loadFeaturedCampaignApp = function() {
    var html = document.getElementsByTagName('html')[0];
    html.dataset.ngApp = 'PullrCampaignApp';

    var body = document.getElementsByTagName('body')[0];
    body.dataset.ngController = 'PullrCampaignCtrl';

    var app = angular.module('PullrCampaignApp', []);
    app.constant({

    });
    app.controller('PullrCampaignCtrl', function($scope, $timeout, PullrCampaignData) {
        $scope.LAYOUT_TYPE_SINGLE = PullrCampaign.LAYOUT_TYPE_SINGLE;
        $scope.LAYOUT_TYPE_MULTI = PullrCampaign.LAYOUT_TYPE_MULTI;
        $scope.LAYOUT_TYPE_TEAM = PullrCampaign.LAYOUT_TYPE_TEAM;

        $scope.selectedCampaign = null;
        $scope.isDataReady = false;
        $scope.campaignDataService = PullrCampaignData;
        $scope.campaignDataService.fetchCampaignData();
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
                var top = $('#watch_window').offset().top;
                $("html, body").animate({ scrollTop: top }, 600);
            }, 50);
        }
    });

    app.service('PullrCampaignData', function($http) {
        var Service = this;
        Service.campaignData = [];
        Service.fetchCampaignData = function() {
            $http.post(PullrCampaign.API_URL + '/campaign_data').success(function(data) {
                Service.campaignData = data;
            });
        }
        return Service;
    });

    app.filter('trusted', ['$sce', function ($sce) {
        return function(url) {
            return $sce.trustAsResourceUrl(url);
        };
    }]);

    angular.element(document).ready(function() {
        angular.bootstrap(document, ['PullrCampaignApp']);
    });
}

PullrCampaign.init();