(function () {
    var app = angular.module('pullr.streamboard.regions', ['pullr.common', 'angular-bootstrap-select', 'angular-bootstrap-select.extra']);
    app.run(function ($rootScope, $http) {
        $rootScope.GOOGLE_FONTS = [];
        var fonts;
        if (fonts = localStorage.getItem('GOOGLE_FONTS')){
           $rootScope.GOOGLE_FONTS = JSON.parse(fonts);
        } else {
            /*here is float bug can be (rare case, only for first load) - fontStyle directive doesn't rerender after update of GOOGLE_FONTS variable*/
            $http.get('https://www.googleapis.com/webfonts/v1/webfonts?key='+$rootScope.Pullr.params.googleAPIKey).success(function(data){
                var items = data.items;
                var fonts = [];
                for (var key in items){
                    var item = items[key];
                    fonts.push({name: item.family, value: item.family});
                }
                localStorage.setItem('GOOGLE_FONTS', JSON.stringify(fonts));
                $rootScope.GOOGLE_FONTS = fonts;
            });
        }
    });
    app.directive('isolatedScope', function () {
        return {
            scope: true
        }
    });
    app.directive('fontStyle', function ($rootScope, $http, $timeout) {
        return {
            scope: {
                model: '=ngModel'
            },
            controller: function ($scope) {
                $scope.regionChanged = function(){
                    /**to have $parent scope updated we are waiting to the next $digest cycle*/
                    $timeout( function(){
                        var $parent = $scope.$parent;
                        var region = $parent.region;
                        $parent.regionChanged(region);
                    });
                }
            },
            templateUrl: 'angular/views/streamboard/region/fontStyle.html'
        }
    });
    app.controller('RegionCtrl', function ($rootScope, $scope, $http) {
        $scope.regions = {};
        $http.get('app/streamboard/get_regions_ajax').success(function (data) {
            $scope.regions = data;
        });
        $scope.regionChanged = function (region) {
            $http.post('app/streamboard/update_region_ajax', region);
        };
    });
})()