(function () {
    var app = angular.module('pullr.streamboard.donations', ['pullr.common', 'pullr.streamboard.campaigns' ]);
    app.filter('selectedCampaigns', function(campaigns){
        return function(donations){
            var filteredDonations = [];
            for (var key in donations){
                var donation = donations[key];
                var campaign = campaigns.campaigns[donation.campaignId];
                if (campaign && campaign.streamboardSelected){
                    filteredDonations.push(donation);
                }
            }
            return filteredDonations;
        }
    });
    app.controller('DonationsCtrl', function($rootScope, $scope, $http, campaigns) {
        $scope.campaignsService = campaigns;
        $scope.donations = [];
        $scope.unorderedDonations = {};
        $scope.stats = {};
        /*we will request only donations with id>$scope.lastDonationId*/
        $scope.lastDonationId = 0;

        $scope.addDonation = function() {
            $http.post('app/streamboard/add_donation_ajax').success(function() {
                updateDonations();
            });
        };


        function sortDonations(){
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
        function updateDonations(forceAll) {
            var params = {since_id: forceAll ? 0 : $scope.lastDonationId };
            $http.get('app/streamboard/get_donations_ajax', {params: params }).success(function(data){
                $scope.stats = data.stats;
                for (var key in data.donations){
                    var donation = data.donations[key];
                    $scope.unorderedDonations[donation.id] = donation;
                }
                if (data.donations.length > 0){
                    $scope.lastDonationId = data.donations[0].id;
                    sortDonations();
                }
            });
        };

        updateDonations();

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
            updateDonations();
        }, 1000);
    });
})();
