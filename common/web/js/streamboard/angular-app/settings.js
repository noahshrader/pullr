(function(){
    var app = angular.module('pullr.streamboard.settings', ['pullr.common', 'pullr.streamboard.campaigns']);
    app.controller('SettingsCtrl', function ($scope,campaigns){
        $scope.campaignsService = campaigns;
    });
})()