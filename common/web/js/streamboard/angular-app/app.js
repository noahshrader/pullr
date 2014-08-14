(function () {
    var app = angular.module('streamboardApp', ['vr.directives.slider', 'pullr.streamboard.donations', 'pullr.streamboard.regions', 'pullr.streamboard.settings']);
    app.run(function ($rootScope, $http) {
        $rootScope.clearDonations = function () {
            $rootScope.unorderedDonations = {};
            $rootScope.donations = [];
            $http.post('app/streamboard/clear_button_ajax');
        };
    });

    app.controller('SourceCtrl', function ($rootScope, $scope, $http) {
        $scope.stats = {};
        function requestSourceStats() {
            $http.get('app/streamboard/get_source_data').success(function (data) {
                $scope.stats = data['stats'];
                $scope.donors = data['donors'];
                $scope.twitchUser = data['twitchUser'];
                $scope.subscribers = data['subscribers'];
            });
        }

        requestSourceStats();
        setInterval(function () {
            requestSourceStats();
        }, 1000);
    });

})();