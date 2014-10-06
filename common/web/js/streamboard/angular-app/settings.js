(function(){
    var app = angular.module('pullr.streamboard.settings', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations']);
    app.controller('SettingsCtrl', function ($scope,campaigns, stream, donations, $http){
        $scope.campaignsService = campaigns;
        $scope.streamService = stream;
        $scope.clearButton = function () {
            donations.clear();
            $http.post('app/streamboard/clear_button_ajax');
        };
    });
})()