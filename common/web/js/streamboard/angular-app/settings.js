(function(){
    var app = angular.module('pullr.streamboard.settings', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations', 'simpleMarquee']);
    app.controller('SettingsCtrl', function ($scope,campaigns, stream, donations, $http, $interval, simpleMarqueeHelper){
        $scope.campaignsService = campaigns;
        $scope.streamService = stream;
        $scope.clearButton = function () {
            donations.clear();
            $http.post('app/streamboard/clear_button_ajax');
        };

        var lastSourceHeight = 0;
        var frame = angular.element('#frame');
        $interval(function(){
        	var height = angular.element('body', frame.contents()).height();
        	if (height != 0 && lastSourceHeight != height) {
        		frame.height(height);
        		lastSourceHeight = height;
        	}
        }, 3000);

        $scope.toggleSubscriber = function() {
            stream.toggleSubscriber();
            simpleMarqueeHelper.recalculateMarquee();
        }

        $scope.toggleFollower = function() {
            stream.toggleFollower();
            simpleMarqueeHelper.recalculateMarquee();
        }

        $scope.toggleGroupUser = function() {
            stream.toggleGroupUser();
            simpleMarqueeHelper.recalculateMarquee();
        }
    });
})()