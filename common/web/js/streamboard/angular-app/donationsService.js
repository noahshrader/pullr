(function () {
    var app = angular.module('pullr.streamboard.donations', []).
        service('donations',function ($http, $interval, $compile) {
            var Service = this;
            this.donations = [];
            this.followers = [];
            this.subscribers = [];
            this.unorderedDonations = {};
            this.lastDonationId = 0;
            this.stats = [];

            this.updateDonations = updateDonations;
            this.clear = clear;

            this.updateDonations();

            $interval(function () {
                Service.updateDonations();
            }, 3000);


            function updateDonations(forceAll) {
                var params = {since_id: forceAll ? 0 : Service.lastDonationId };
                $http.get('app/streamboard/get_donations_ajax', {params: params }).success(function (data) {
                    Service.stats = data.stats;
                    for (var key in data.donations) {
                        var donation = data.donations[key];
                        Service.unorderedDonations[donation.id] = donation;
                    }

                    Service.followers = data.followers;
                    Service.subscribers = data.subscribers;
                    if (!data.donations) {
                        console.log('[ERROR]');
                        console.log('[donationsService.js -> updateDonations] No donations array in response');
                        console.log('[RESPONSE]', new Date().getTime() / 1000);
                    } else if (data.donations.length > 0) {
                        Service.lastDonationId = data.donations[0].id;
                        sortDonations();

                    }
                });                
            };

            function sortDonations() {
                var newDonations = [];
                for (var key in Service.unorderedDonations) {
                    var donation = Service.unorderedDonations[key];
                    newDonations.push(donation);
                }
                newDonations.sort(function (a, b) {
                    return b.paymentDate - a.paymentDate;
                });
                Service.donations = newDonations;
                console.log(Service.donations);
            };

            function clear() {
                Service.unorderedDonations = {};
                Service.donations = [];
            }
        }).filter('donationsFilterToSelectedCampaigns', function (campaigns) {
            return function (donations) {
                var filteredDonations = [];
                for (var key in donations) {
                    var donation = donations[key];
                    var campaign = campaigns.campaigns[donation.campaignId];
                    if (campaign && campaign.streamboardSelected) {
                        filteredDonations.push(donation);
                    }
                }
                return filteredDonations;
            }
        });
})();
