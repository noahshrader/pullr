(function() {
    var app = angular.module('pullr.streamboard.sourceApp', ['pullr.common', 'pullr.streamboard.campaigns']);
    app.controller('SourceCtrl', function ($scope, $http, campaigns){
        $scope.campaignsService = campaigns;
        $scope.stats = {};
        $scope.requestSourceStats = function(){
            $http.get('app/streamboard/get_source_data').success(function(data){
                $scope.stats = data['stats'];
                $scope.donors = data['donors'];
                $scope.twitchUser = data['twitchUser'];
                $scope.subscribers = data['subscribers'];
            });
        }
        $scope.requestSourceStats();
        setInterval(function() {
            $scope.requestSourceStats();
        }, 1000);
    });
})();
