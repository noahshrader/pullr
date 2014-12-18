(function () {
    var app = angular.module('pullr.streamboard.campaigns', []).
        service('campaigns', ['$http', '$interval', '$timeout', function ($http, $interval, $timeout) {
        var Service = this;
        this.selectedCampaignsNumber = 0;
        this.campaigns = {};

        if (typeof(Pullr.Streamboard) != 'undefined' && typeof(Pullr.Streamboard.campaignsData) != 'undefined') {
            var campaigns = $.extend({}, Pullr.Streamboard.campaignsData);
            updateCampaigns(campaigns);
        }

        requestCampaigns();

        function requestCampaigns() {
            $http.get('app/streamboard/get_campaigns_ajax').success(function(campaigns){
                updateCampaigns(campaigns);
                $timeout(function(){
                    requestCampaigns();
                }, 5000)
            });
        };

        function updateCampaigns(campaigns) {
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
        }

        function calcSelectedCampaignsNumber() {
            var number = 0;
            $.each(Service.campaigns, function(key, campaign){
                campaign.streamboardSelected ? number++: null;
            });
            Service.selectedCampaignsNumber = number;
        };



        this.getcampaignName = function(id){
            var name  = "";
            $.each(Service.campaigns, function(key, campaign){
               if(id == campaign.id){
                    name = campaign.name;
               }
            });
            return name;
        }

        this.campaignChanged = function(campaign){
            $http.post('app/streamboard/set_campaign_selection', {
                id: campaign.id,
                streamboardSelected: campaign.streamboardSelected
            });
            calcSelectedCampaignsNumber();
        };
    }])
    .filter('charityCampaigns', [function(){
        return function(campaigns){
            var results = [];
            angular.forEach(campaigns, function(campaign) {
                if (campaign.type == Pullr.CAMPAIGN_TYPE_CHARITY_FUNDRAISER) {
                    results.push(campaign);
                }
            });
            return results;
        }
    }]);
})();
