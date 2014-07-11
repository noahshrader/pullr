(function() {
    var app = angular.module('streamboardApp', []);
    app.controller('DonationsController', function($scope, $http) {
        $scope.donations = [];
        $scope.unorderedDonations = {};
        $scope.stats = {};
        //$scope.campaigns = [];
        /*we will request only donations with id>$scope.lastDonationId*/
        $scope.lastDonationId = 0;
        
        $scope.addDonation = function() {
            $http({
                method: 'POST',
                url: 'app/streamboard/add_donation_ajax',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function() {
                $scope.updateDonations();
            });
        };
        $scope.clearDonations = function(){
            $scope.unorderedDonations = {};
            $scope.donations = [];
        };
        $scope.sortDonations = function(){
            var newDonations = [];
            for (var key in $scope.unorderedDonations){
                var donation = $scope.unorderedDonations[key];
                newDonations.push(donation);
            }
            newDonations.sort(function(a,b){
                return b.paymentDate - a.paymentDate;
            });
            $scope.donations = newDonations;
            
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
            $http.get('app/streamboard/get_donations_ajax', {params: {since_id: $scope.lastDonationId}}).success(function(data){
                for (var key in data.donations){
                    var donation = data.donations[key];
                    $scope.unorderedDonations[donation.id] = donation;
                }
                $scope.stats = data.stats;
                if (data.donations.length > 0){
                    $scope.lastDonationId = data.donations[0].id;
                    $scope.sortDonations();
                }
            });
        };

        $scope.updateDonations();
        setInterval(function() {
            $scope.updateDonations();
        }, 1000);
    });
})();