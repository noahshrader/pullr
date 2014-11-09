(function() {
    var app = angular.module('pullr.streamboard.sourceApp', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.stream','pullr.streamboard.donations','simpleMarquee']);
    app.controller('SourceCtrl', function ($scope, $http, campaigns, stream, donations){
        $scope.campaignsService = campaigns;
        $scope.streamService = stream;
        $scope.donationsService = donations;
        $scope.stats = {};
        isDataReady = false;
        
        $scope.requestSourceStats = function(){
            $http.get('app/streamboard/get_source_data').success(function(data){
                $scope.stats = data['stats'];            
                $scope.twitchUser = data['twitchUser'];              
                $scope.followersNumber = data['followersNumber'];
                $scope.subscribersNumber = data['subscribersNumber'];  
                $scope.emptyActivityMessage = data['emptyActivityMessage'];
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
        }, 5000);
    });
})();
