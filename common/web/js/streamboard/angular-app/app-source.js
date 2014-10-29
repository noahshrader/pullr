(function() {
    var app = angular.module('pullr.streamboard.sourceApp', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.stream']);
    app.controller('SourceCtrl', function ($scope, $http, campaigns, stream){
        $scope.campaignsService = campaigns;
        $scope.streamService = stream;
        $scope.stats = {};
        isDataReady = false;
        console.log('SourceCtrl init');
        $scope.requestSourceStats = function(){
            $http.get('app/streamboard/get_source_data').success(function(data){
                $scope.stats = data['stats'];
                $scope.donors = data['donors'];
                $scope.twitchUser = data['twitchUser'];
                $scope.subscribers = data['subscribers'];
                $scope.followers = data['followers'];
                $scope.followersNumber = data['followersNumber'];
                $scope.subscribersNumber = data['subscribersNumber'];  
                if (false == isDataReady) {
                    angular.element('#source-wrap-angular').show();
                    angular.element('#source-wrap-php').hide();
                    isDataReady = true;
                }            
            });
        }
        $scope.requestSourceStats();
        setInterval(function() {
            $scope.requestSourceStats();
        }, 5000);

        setInterval(function(){
            $scope.streamService.getActivityFeedSetting();
        }, 3000);
    });
})();
