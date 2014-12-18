angular.module('streamboardApp').service('streamboardConfig', ['$http', function($http){
	var service = {};
	service.config = {};
	service.init = function() {
		if (Pullr.Streamboard.streamboardConfig) {
			service.config = Pullr.Streamboard.streamboardConfig;
		} else {
			$http.get('app/streamboard/get_streamboard_config').success(function(data){
				service.config = data;
			});
		}

	}

	service.setSidebarWidth = function(width) {
		service.config.sidePanelWidth = width;
		$http.post('app/streamboard/set_streamboard_sidepanel_width', {
        	width: width
     	});
	}

	service.setRegion2Height = function(height) {
		service.config.region2Height = height;
		$http.post('app/streamboard/set_streamboard_region2_height', {
			height: height
		});
	}

	service.changeEnableFeaturedCampaign = function(enableFeaturedCampaign) {
		$http.post('app/streamboard/set_enable_featured_campaign', {
			enableFeaturedCampaign: enableFeaturedCampaign
		});
    }

    service.changeFeaturedCampaignId = function(featuredCampaignId) {
    	$http.post('app/streamboard/set_featured_campaign_id', {
			featuredCampaignId: featuredCampaignId
		});
    }
	service.init();
	return service;
}]);