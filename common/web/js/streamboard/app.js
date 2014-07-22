(function() {
    var app = angular.module('streamboardApp', []);
    app.filter('selectedCampaigns', function(){
       return function(donations, $rootScope){
           var filteredDonations = [];
           for (var key in donations){
               var donation = donations[key];
               var campaign = $rootScope.campaigns[donation.campaignId];
               if (campaign && campaign.streamboardSelected){
                   filteredDonations.push(donation);
               }
           }
           return filteredDonations;
       }
    });
    app.run(function($rootScope, $http){
        $rootScope.campaigns = {};
        $rootScope.requestCampaigns = function() {
            $http.get('app/streamboard/get_campaigns_ajax').success(function(data){
                $rootScope.campaigns = data;
            });
        };
        $rootScope.requestCampaigns();
    });

    app.controller('SourceCtrl', function ($scope, $rootScope){

    });

    app.controller('DonationsCtrl', function($scope, $http, $rootScope) {
        $scope.donations = [];
        $scope.unorderedDonations = {};
        $scope.stats = {};
        $scope.selectedCampaignsNumber = 0;
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
        $scope.updateDonations = function(forceAll) {
            var params = {since_id: forceAll ? 0 : $scope.lastDonationId };
            $http.get('app/streamboard/get_donations_ajax', {params: params }).success(function(data){
                $scope.stats = data.stats;
                $scope.calcSelectedCampaignsNumber();
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
            $scope.calcSelectedCampaignsNumber();
        };

        $scope.calcSelectedCampaignsNumber = function(){
            var number = 0;
            $.each($scope.campaigns, function(key, campaign){
                campaign.streamboardSelected ? number++: null;
            });
            $scope.selectedCampaignsNumber = number;
        };
        $scope.updateDonations();

        $scope.nameHiddenToggle = function(donation){
            donation.streamboard.nameHidden = !donation.streamboard.nameHidden;
            donation.displayName = donation.streamboard.nameHidden || !donation.nameFromForm ? Pullr.ANONYMOUS_NAME : donation.nameFromForm;
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'nameHidden', value: donation.streamboard.nameHidden }).success(function(){
                /*let's update name for all donations*/
                $scope.updateDonations(true);
            });
        }

        $scope.markAsRead = function(donation){
            donation.streamboard.wasRead = true;
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'wasRead', value: true });
        }

        setInterval(function() {
            $scope.updateDonations();
        }, 1000);
    });
})();