(function () {
    var app = angular.module('pullr.streamboard.donations', []).
        service('donations', ['$http',
                            '$interval',
                            '$timeout',
                            '$compile',
                            'stream',
                            'simpleMarqueeHelper',
                            'donationsFilterToSelectedCampaignsFilter',
                            'groupByFilter',
                            'orderByFilter',
                            'limitToFilter',
            function ($http,
                    $interval,
                    $timeout,
                    $compile,
                    stream,
                    simpleMarqueeHelper,
                    donationsFilterToSelectedCampaignsFilter,
                    groupByFilter,
                    orderByFilter,
                    limitToFilter) {

                var Service = this;
                this.donations = [];

                this.donationsByName = [];
                this.donationsByEmail = [];

                this.followers = [];
                this.subscribers = [];
                this.unorderedDonations = {};
                this.lastDonationId = 0;
                this.stats = [];
                this.groupDonations = [];
                this.groupDonationsByEmail = [];
                this.groupDonationsByName = [];

                this.updateDonations = updateDonations;
                this.clear = clear;

                if (Pullr.Streamboard != undefined && Pullr.Streamboard.donationsData != undefined) {
                    var donationsData = Pullr.Streamboard.donationsData;
                    this.donations = donationsData.donations;
                    this.stats = donationsData.stats;
                    this.followers = donationsData.followers;
                    this.subscribers = donationsData.subscribers;
                    this.userDonations = donationsData.userDonations;
                }

                autoUpdateDonation();

                function autoUpdateDonation() {
                    updateDonations(false, function () {
                        $timeout(function () {
                            autoUpdateDonation();
                        }, 5000);
                    });
                }

                function updateDonations(forceAll, callback) {
                    callback = callback || function () {
                    };
                    var params = {since_id: forceAll ? 0 : Service.lastDonationId};
                    $http.get('app/streamboard/get_donations_ajax', {params: params}).success(function (data) {
                        Service.stats = data.stats;
                        for (var key in data.donations) {
                            var donation = data.donations[key];
                            Service.unorderedDonations[donation.id] = donation;
                        }

                        Service.followers = data.followers;
                        Service.subscribers = data.subscribers;

                        if (data.userDonations) {
                            Service.userDonations = donationsFilterToSelectedCampaignsFilter(data.userDonations);
                        }
                        Service.donationsByEmail = data.donationsByEmail;
                        Service.donationsByName = data.donationsByName;
                        groupDonations();

                        callback();
                    });
                };

                function groupDonations() {
                    if (Service.donationsByEmail) {
                        var groupDonations = orderByFilter(Service.donationsByEmail, 'amount');
                        groupDonations = donationsFilterToSelectedCampaignsFilter(groupDonations);
                        groupDonations = groupByFilter(groupDonations, 'amount');
                        Service.groupDonationsByEmail = groupDonations;
                    }

                    if (Service.donationsByName) {
                        var groupDonations = orderByFilter(Service.donationsByName, 'amount');
                        groupDonations = donationsFilterToSelectedCampaignsFilter(groupDonations);
                        groupDonations = groupByFilter(groupDonations, 'amount');
                        Service.groupDonationsByName = groupDonations;
                    }
                    return true;
                }

                function clear() {
                    Service.unorderedDonations = {};
                    Service.donations = [];
                }
            }]).filter('donationsFilterToSelectedCampaigns', function (campaigns) {
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
        }).filter('groupBy', [function () {
            var groupBy = function (list, groupBy) {
                var groupedList = [];
                var checkList = [];
                angular.forEach(list, function (item, index) {
                    if (checkList.indexOf(item[groupBy]) == -1) {
                        lastGroupValue = item[groupBy];
                        checkList.push(lastGroupValue);
                        var group = {
                            amount: lastGroupValue,
                            items: [item]
                        };

                        angular.forEach(list, function (item1, index1) {
                            if (lastGroupValue == item1[groupBy] && index1 > index) {
                                group.items.push(item1);
                            }
                        });

                        groupedList.push(group);
                    }
                });

                groupedList.sort(function (a, b) {
                    return b.amount - a.amount;
                });

                return groupedList;
            }
            return groupBy;
        }]);
})();
