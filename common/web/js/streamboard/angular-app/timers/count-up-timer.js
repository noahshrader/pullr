(function () {
    'use strict';
    angular.module('pullr.countUpTimer', ['timer'])
        .directive('countUpTimer', ['$compile', function ($compile) {

            function link(scope, element, attrs) {
                var tpl = '<div>' +
                    '<span ng-show="days">{{days}} day{{daysS}},</span> ' +
                    '<span ng-show="hours || days">{{hours}} hour{{hoursS}},</span> ' +
                    '<span ng-show="minutes || hours || days">{{minutes}} minute{{minutesS}},</span> ' +
                    '<span>{{seconds}} second{{secondsS}}.</span>' +
                    '</div>';

                function updateTime() {
                    var time;
                    if (!scope.module.countUpPauseTime) {
                        //e.g. first open of streamboard
                        time = 0;
                    } else {
                        time = new Date(scope.module.countUpPauseTime) - new Date(scope.module.countUpStartTime);
                    }

                    var seconds = Math.floor(time / 1000);
                    scope.seconds = seconds % 60;
                    if (scope.seconds !== 1) {
                        scope.secondsS = 's';
                    }

                    var minutes = Math.floor(seconds / 60);
                    scope.minutes = minutes % 60;
                    if (scope.minutes !== 1) {
                        scope.minutesS = 's';
                    }

                    var hours = Math.floor(minutes / 60);
                    scope.hours = hours % 60;
                    if (scope.hours !== 1) {
                        scope.hoursS = 's';
                    }

                    scope.days = Math.floor(hours / 24);
                    if (scope.days !== 1) {
                        scope.daysS = 's';
                    }

                    var el = angular.element(tpl);
                    var compiled = $compile(el)(scope);
                    element.html(compiled);
                }

                scope.$watch("module.countUpPauseTime", function () {
                    updateTime();
                })
            }

            return {
                link: link
            };
        }]);
})();