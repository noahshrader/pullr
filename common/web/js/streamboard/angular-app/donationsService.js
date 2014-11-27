(function () {
    var app = angular.module('pullr.streamboard.donations', []).
        service('donations',['$http', '$interval', '$timeout', '$compile', 'stream', 'simpleMarqueeHelper', 'donationsFilterToSelectedCampaignsFilter', 'groupByFilter', 'orderByFilter', 'limitToFilter',
                function ($http, $interval, $timeout, $compile, stream, simpleMarqueeHelper, donationsFilterToSelectedCampaignsFilter, groupByFilter, orderByFilter, limitToFilter) {
                    var Service = this;
                    this.donations = [];
                    this.followers = [];
                    this.subscribers = [];
                    this.unorderedDonations = {};
                    this.lastDonationId = 0;
                    this.stats = [];
                    this.groupDonations = [];
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
                        updateDonations(false, function(){
                            $timeout(function() {
                                autoUpdateDonation();
                            }, 5000);
                        });
                    }
        
                    function updateDonations(forceAll, callback) {
                        callback = callback || function(){};
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
                            
                            if (data.userDonations) {
                                Service.userDonations = data.userDonations;
                            }
        
                            if (data.donations) {
                                Service.donations = data.donations;
                                groupDonations();                       
                                if ( detectChange(data.donations, Service.donations)) {
                                    simpleMarqueeHelper.recalculateMarquee();                            
                                }                                                                        
                            }
        
                            callback();
        
                            
                        });                
                    };
        
                    function detectGroupDonationChange(groupDonations1, groupDonations2) {                
                        if (countGroupDonationItem(groupDonations1) != countGroupDonationItem(groupDonations2)) {
                            return true;
                        }
                        return false;
                    }
        
                    function countGroupDonationItem(groupDonations) {
                        var count = 0;
                        angular.forEach(groupDonations, function(item){
                            count++;
                        });
                        return count;
                    }
        
                    function detectChange(streamArray1, streamArray2) {
                        if (streamArray2.length != streamArray1.length) {
                            return true;
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
        
                    function groupDonations() {           
                        var groupDonations = orderByFilter(Service.donations, 'amount');               
                        groupDonations = donationsFilterToSelectedCampaignsFilter(groupDonations, 'amount');                               
                        groupDonations = groupByFilter(groupDonations, 'amount');
                        Service.groupDonations = groupDonations;        
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
        }).filter('groupBy', [function() {
            var groupBy = function(list, groupBy) {
                var groupedList = [];      
                var checkList = [];          
                angular.forEach(list, function(item, index) {
                    if (checkList.indexOf(item[groupBy]) == -1) {                 
                        lastGroupValue = item[groupBy];                        
                        checkList.push(lastGroupValue);
                        var group = {
                            amount: lastGroupValue,
                            items: [item]
                        };
                                            
                        angular.forEach(list, function(item1, index1) {
                            if ( lastGroupValue == item1[groupBy] && index1 > index) {
                                group.items.push(item1);
                            }
                        });

                        groupedList.push(group);
                    }                    
                });

                groupedList.sort(function(a,b) {
                    return b.amount - a.amount;
                });                                          

                return groupedList;
            }
            return groupBy;
        }]);
})();
