(function(){
    var app = angular.module('pullr.streamboard.settings', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations']);
    app.controller('SettingsCtrl', function ($scope,campaigns, donationsService, $http){
        $scope.campaignsService = campaigns;
        $scope.clearButton = function () {
            log(donationsService);
            donationsService.unorderedDonations = {};
            donationsService.donations = [];
            $http.post('app/streamboard/clear_button_ajax');
            log(donationsService);
        };
    });
})()