(function() {
    var app = angular.module('streamboardApp', []);
    app.filter('selectedCampaigns', function(){
       return function(donations, $scope){
           var filteredDonations = [];
           for (var key in donations){
               var donation = donations[key];
               var campaign = $scope.campaigns[donation.campaignId];
               if (campaign && campaign.streamboardSelected){
                   filteredDonations.push(donation);
               }
           }
           return filteredDonations;
       }
    });
    app.controller('DonationsController', function($scope, $http) {
        $scope.donations = [];
        $scope.campaigns = {};
        $scope.unorderedDonations = {};
        $scope.stats = {};
        //$scope.campaigns = [];
        /*we will request only donations with id>$scope.lastDonationId*/
        $scope.lastDonationId = 0;
        
        $scope.addDonation = function() {
            $http.post('app/streamboard/add_donation_ajax').success(function() {
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
            
        };
        $scope.updateDonations = function() {
            $http.get('app/streamboard/get_donations_ajax', {params: {since_id: $scope.lastDonationId}}).success(function(data){
                $scope.stats = data.stats;
                if ($.isEmptyObject($scope.campaigns)){
                    $scope.campaigns = data.campaigns;
                }
                for (var key in data.donations){
                    var donation = data.donations[key];
                    $scope.unorderedDonations[donation.id] = donation;
                }
                if (data.donations.length > 0){
                    $scope.lastDonationId = data.donations[0].id;
                    $scope.sortDonations();
                }
                
            });
        };
        $scope.campaignChanged = function(campaign){
            $http.post('app/streamboard/set_campaign_selection', {id: campaign.id, streamboardSelected: campaign.streamboardSelected});
        }
        $scope.updateDonations();
        
        setInterval(function() {
            $scope.updateDonations();
        }, 1000);
    });
})();