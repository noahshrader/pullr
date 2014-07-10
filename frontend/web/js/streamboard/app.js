(function() {
    var app = angular.module('streamboardApp', []);
    app.controller('DonationsController', function($scope, $http) {
        $scope.donations = [];
        $scope.stats = {};
        //$scope.campaigns = [];

        $scope.addDonation = function() {
            $http({
                method: 'POST',
                url: 'app/streamboard/add_donation_ajax',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data) {
            });
        }
        $scope.updateDonations = function() {
//            var selected_campaigns = [];
//            if (typeof $scope.campaigns !== 'undefined') {
//                $.each($scope.campaigns, function(index, value) {
//                    if (String(value) == 'true') {
//                        selected_campaigns.push(index);
//                    }
//                });
//            }

//            var request_data = {
//                streamboard_launch_time: $scope.start_time,
//                selected_campaigns: selected_campaigns,
//            };
            $http.get('app/streamboard/get_donations_ajax').success(function(data){
                $scope.donations = data.donations;
                $scope.stats = data.stats;
            });
        };

        $scope.updateDonations();
        setInterval(function() {
            $scope.updateDonations();
        }, 1000);
    });
})();