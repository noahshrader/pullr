(function() {
    var app = angular.module('streamboardApp', ['vr.directives.slider', 'pullr.streamboard.donations', 'pullr.streamboard.regions']);
    app.run(function($rootScope, $http){
        $rootScope.clearDonations = function(){
            $rootScope.unorderedDonations = {};
            $rootScope.donations = [];
            $http.post('app/streamboard/clear_button_ajax');
        };
    });

    app.controller('SourceCtrl', function ($rootScope, $scope, $http){
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