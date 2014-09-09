(function () {
    var app = angular.module('pullr.streamboard.donationsPanel', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations']);
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
    app.controller('DonationsCtrl', function($scope, $http, campaigns, donations) {
        $scope.campaignsService = campaigns;
        $scope.donationsService = donations;

        /**that is just debug option*/
        /**@todo to be removed*/
        $scope.addDonation = function() {
            $http.post('app/streamboard/add_donation_ajax').success(function() {
                donations.updateDonations();
            });
        };



        $scope.nameHiddenToggle = function(donation){
            donation.streamboard.nameHidden = !donation.streamboard.nameHidden;
            donation.displayName = donation.streamboard.nameHidden || !donation.nameFromForm ? Pullr.ANONYMOUS_NAME : donation.nameFromForm;
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'nameHidden', value: donation.streamboard.nameHidden }).success(function(){
                /*let's update name for all donations*/
                updateDonations(true);
            });
        }

        $scope.markAsRead = function(donation){
            donation.streamboard.wasRead = true;
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'wasRead', value: true });
        }

    });
})();
