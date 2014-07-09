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
        var updateDonations = function() {
            var selected_campaigns = [];
            if (typeof $scope.campaigns !== 'undefined') {
                $.each($scope.campaigns, function(index, value) {
                    if (String(value) == 'true') {
                        selected_campaigns.push(index);
                    }
                });
            }

            var request_data = {
                streamboard_launch_time: $scope.start_time,
                selected_campaigns: selected_campaigns,
            };
            request_data[$scope.csrf_token_name] = $scope.csrf_token;
//        request_data['X-CSRF-Token'] = pub.getCsrfToken();


            request_data = $.param(request_data);
            $http({
                method: 'POST',
                url: 'app/streamboard/get_donations_ajax',
                data: request_data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data) {
                $scope.donations = data.donations;
                $scope.stats = data.stats;
            });
        };

        updateDonations();
        setInterval(function() {
            $scope.$apply(updateDonations);
        }, 3000);
    });
})();