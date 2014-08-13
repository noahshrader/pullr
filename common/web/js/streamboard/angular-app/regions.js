(function(){
    var app = angular.module('pullr.streamboard.regions', ['pullr.common']);
    app.run(function($rootScope, $http){
        $rootScope.GOOGLE_FONTS = [];
        $http.get('https://www.googleapis.com/webfonts/v1/webfonts?key='+$rootScope.Pullr.params.googleAPIKey).success(function(data){
            var items = data.items;
            var fonts = [];
            for (var key in items){
                var item = items[key];
                fonts.push({name: item.family, value: item.family});
            }
            $rootScope.GOOGLE_FONTS = fonts;
        });
    });
    app.directive('isolatedScope', function(){
        return {
            scope: true
        }
    });
    app.controller('RegionCtrl', function ($rootScope, $scope, $http){
        $scope.regions = {};
        $http.get('app/streamboard/get_regions_ajax').success(function(data){
            $scope.regions = data;
        });
        $scope.regionChanged = function(region){
            $http.post('app/streamboard/update_region_ajax', region);
        };
    });
})()