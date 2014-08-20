(function () {
    var app = angular.module('pullr.common', []);
    app.filter('replace', function () {
            return function (input, regex,replaceTo ) {
                var pattern = new RegExp(regex,'g');
                return input.replace(pattern, replaceTo)
            }
        }
    );
    app.run(function ($rootScope) {
        $rootScope.number_format = number_format;
        $rootScope.length = function (obj) {
            return Object.keys(obj).length;
        }
        $rootScope.Pullr = Pullr;
    });
})();
