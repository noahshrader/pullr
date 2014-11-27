(function () {
    var app = angular.module('pullr.common', []);
    app.filter('replace', [function () {
        return function (input, regex,replaceTo ) {
            var pattern = new RegExp(regex,'g');
            return input.replace(pattern, replaceTo)
        }
    }]);
    /*return filename without extension*/
    app.filter('fileName', [function () {
        return function (input) {
            return input.substr(0, input.lastIndexOf('.')) || input;
        }
    }]);
    /*that mean new variables will be isolated inside new scope
    * but old variables will be available is they will be taken from parent scope*/
    app.directive('childScope', [function () {
        return {
            scope: true
        }
    }]);

    /*
     * directive to focus on show
     */
    app.directive('focusOn',['$timeout', function($timeout) {
        return {
            restrict : 'A',
            link : function($scope,$element,$attr) {
                $scope.$watch($attr.focusOn,function(_focusVal) {
                    $timeout(function() {
                        _focusVal ? $element.focus() :
                            $element.blur();
                    });
                });
            }
        }
    }])

    app.run(['$rootScope', function ($rootScope) {
        $rootScope.number_format = number_format;
        $rootScope.length = function (obj) {
            return Object.keys(obj).length;
        }
        $rootScope.Pullr = Pullr;
    }]);
})();
