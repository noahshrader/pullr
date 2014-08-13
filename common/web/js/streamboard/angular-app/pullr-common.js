(function() {
    var app = angular.module('pullr.common', []);
    app.run(function($rootScope){
        $rootScope.number_format = number_format;
        $rootScope.length = function(obj){
            return Object.keys(obj).length;
        }
        $rootScope.user = Pullr.user;
        $rootScope.Pullr = Pullr;
    });
})();
