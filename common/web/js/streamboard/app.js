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
        $rootScope.selectedCampaignsNumber = 0;
        $rootScope.campaigns = {};
        $rootScope.number_format = number_format;
        $rootScope.length = function(obj){
            return Object.keys(obj).length;
        }
        $rootScope.user = Pullr.user;
        $rootScope.requestCampaigns = function() {
            $http.get('app/streamboard/get_campaigns_ajax').success(function(campaigns){
                var oldCampaigns = $rootScope.campaigns;
                /*We have asynchronous conflict between campaigns enabling/disabling and requestCampaigns request.
                So first we prefer streamboardSelected value at client compared to server.
                 */
                for (var id in campaigns){
                   if (oldCampaigns[id]){
                      campaigns[id].streamboardSelected = oldCampaigns[id].streamboardSelected;
                   }
                }
                $rootScope.campaigns = campaigns;
                $rootScope.calcSelectedCampaignsNumber();
            });
        };
        $rootScope.calcSelectedCampaignsNumber = function(){
            var number = 0;
            $.each($rootScope.campaigns, function(key, campaign){
                campaign.streamboardSelected ? number++: null;
            });
            $rootScope.selectedCampaignsNumber = number;
        };
        $rootScope.requestCampaigns();
        setInterval(function() {
            $rootScope.requestCampaigns();
        }, 1000);

    });

    app.controller('SourceCtrl', function ($rootScope, $scope, $http){
        $scope.stats = {};
        $scope.requestSourceStats = function(){
           $http.get('app/streamboard/get_source_data').success(function(data){
              $scope.stats = data['stats'];
              $scope.donors = data['donors'];
              $scope.twitchUser = data['twitchUser'];
              $scope.subscribers = data['subscribers'];
           });
        }
        $scope.requestSourceStats();
        setInterval(function() {
            $scope.requestSourceStats();
        }, 1000);
    });

    app.controller('DonationsCtrl', function($rootScope, $scope, $http) {
        $scope.donations = [];
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
            $http.post('app/streamboard/clear_button_ajax');
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
            $rootScope.calcSelectedCampaignsNumber();
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