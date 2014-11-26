var app = angular.module('regionApp',['pullr.streamboard.regionsPanels','streamboardApp']);
app.factory('streamboardTokenInterceptor', function() {
	var streamboardTokenInterceptor = {
		request: function(config) {			
			if (Pullr.Streamboard.streamboardToken) {
				config.headers['streamboardToken'] = Pullr.Streamboard.streamboardToken;
			}
			return config;
		}
	}
	return streamboardTokenInterceptor;
});

app.config(function($httpProvider) {
	$httpProvider.interceptors.push('streamboardTokenInterceptor');
});

app.run(function(){
	$(".spinner-wrap").addClass('powered').fadeOut();
});
