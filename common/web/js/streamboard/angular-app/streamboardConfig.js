angular.module('streamboardApp').factory('streamboardConfig', function($http){
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
		$http.post('app/streamboard/set_streamboard_sidepanel_width', {
        	width: width
     	});
	}

	service.setRegion2Height = function(height) {
		$http.post('app/streamboard/set_streamboard_region2_height', {
			height: height
		});
	}
	service.init();
	return service;
});