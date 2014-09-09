(function () {
    var app = angular.module('pullr.streamboard.donations', []).
        service('donations', function ($http, $interval) {
            var Service = this;
            this.donations = [];
            this.unorderedDonations = {};
            this.lastDonationId = 0;
            this.stats = [];

            function sortDonations(){
                var newDonations = [];
                for (var key in Service.unorderedDonations){
                    var donation = Service.unorderedDonations[key];
                    newDonations.push(donation);
                }
                newDonations.sort(function(a,b){
                    return b.paymentDate - a.paymentDate;
                });
                Service.donations = newDonations;
            };

            this.updateDonations = function(forceAll)  {
                var params = {since_id: forceAll ? 0 : Service.lastDonationId };
                $http.get('app/streamboard/get_donations_ajax', {params: params }).success(function(data){
                    Service.stats = data.stats;
                    for (var key in data.donations){
                        var donation = data.donations[key];
                        Service.unorderedDonations[donation.id] = donation;
                    }
                    if (!data.donations){
                        console.log('[ERROR]');
                        console.log('[donationsService.js -> updateDonations] No donations array in response');
                        console.log('[RESPONSE]', new Date().getTime() / 1000);
                    } else
                    if (data.donations.length > 0){
                        Service.lastDonationId = data.donations[0].id;
                        sortDonations();
                    }
                });
            };

            this.updateDonations();

            $interval(function() {
                Service.updateDonations();
            }, 1000);
        });
})();
