var PullrStatistic = {};
PullrStatistic.MAIN_URL = "//<?=$_SERVER['HTTP_HOST']?><?= \Yii::$app->urlManager->baseUrl?>/";
PullrStatistic.ANGULAR_LIB_URL = Pullr.MAIN_URL + 'public/angular-1.3.4.min.js';
PullrStatistic.API_URL = PullrStatistic.MAIN_URL + 'app/statistic';


PullrStatistic.init = function() {
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = PullrStatistic.ANGULAR_LIB_URL;
    script.async = true;
    script.onload = function(){
        PullrStatistic.loadStatsticApp();
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}

PullrStatistic.loadStatsticApp = function() {
    var html = document.getElementsByTagName('html')[0];
    html.dataset.ngApp = 'PullrStatisticApp';

    var body = document.getElementsByTagName('body')[0];
    body.dataset.ngController = 'PullrStatisticCtrl';

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

    angular.element(document).ready(function() {
        angular.bootstrap(document, ['PullrStatisticApp']);
    });
}

PullrStatistic.init();