(function () {
    var app = angular.module('pullr.streamboard.donations', []).
        service('donations',function ($http, $interval, $compile, simpleMarqueeHelper) {
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
                    var oldFollowers = Service.followers;
                    var oldSubscribers = Service.subscribers;

                    Service.followers = data.followers;
                    Service.subscribers = data.subscribers;

                    if (detectChange(data.subscribers, oldSubscribers) || detectChange(data.followers, oldFollowers)) {
                        simpleMarqueeHelper.recalculateMarquee();
                    }

                    if (!data.donations) {
                        console.log('[ERROR]');
                        console.log('[donationsService.js -> updateDonations] No donations array in response');
                        console.log('[RESPONSE]', new Date().getTime() / 1000);
                    } else if (data.donations.length > 0) {
                        Service.lastDonationId = data.donations[0].id;
                        if ( detectChange(data.donations, Service.donations)) {
                            simpleMarqueeHelper.recalculateMarquee();
                        }
                        sortDonations();

                    }
                });                
            };

            function detectChange(streamArray1, streamArray2) {
                var found = false;
                for (var i=0; i<streamArray1.length; i++) {
                    for(var j=0; j<streamArray2.length; j++) {
                        if(streamArray1[i].id == streamArray2[j].id) {
                            found = true;
                            break;
                        }
                    }
                    if (found == false) {
                        return true;
                    }
                }
                return false;
            }
            function sortDonations() {
                var newDonations = [];
                for (var key in Service.unorderedDonations) {
                    var donation = Service.unorderedDonations[key];
                    newDonations.push(donation);
                }
                newDonations.sort(function (a, b) {
                    return b.amount - a.amount;
                });
                Service.donations = newDonations;             
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
