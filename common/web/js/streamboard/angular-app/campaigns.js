(function () {
    var app = angular.module('pullr.streamboard.campaigns', []).
        service('campaigns', function ($http) {
            var Service = this;
            this.selectedCampaignsNumber = 0;
            this.campaigns = {};
            function requestCampaigns() {
                $http.get('app/streamboard/get_campaigns_ajax').success(function(campaigns){
                    var oldCampaigns = Service.campaigns;
                    /*We have asynchronous conflict between campaigns enabling/disabling and requestCampaigns request.
                     So first we prefer streamboardSelected value at client compared to server.
                     */
                    for (var id in campaigns){
                        if (oldCampaigns[id]){
                            campaigns[id].streamboardSelected = oldCampaigns[id].streamboardSelected;
                        }
                    }
                    Service.campaigns = campaigns;
                    calcSelectedCampaignsNumber();
                });
            };
            function calcSelectedCampaignsNumber(){
                var number = 0;
                $.each(Service.campaigns, function(key, campaign){
                    campaign.streamboardSelected ? number++: null;
                });
                Service.selectedCampaignsNumber = number;
            };
            requestCampaigns();

            setInterval(function() {
                requestCampaigns();
            }, 1000);

            this.campaignChanged = function(campaign){
                $http.post('app/streamboard/set_campaign_selection', {id: campaign.id, streamboardSelected: campaign.streamboardSelected});
                calcSelectedCampaignsNumber();
            };
        });
})();
