(function () {
    'use strict';
    angular.module('pullr.currentTime', [])
        .directive('currentTime', ['$interval','$filter', function ($interval, $filter) {

            function link(scope, element, attrs) {
                var format = 'M/d/yy h:mm:ss a',
                    timeoutId;

                function updateTime() {
                    element.text($filter('date')(new Date(), format));
                }

                if (attrs.format){
                    format = attrs.format;
                }

                element.on('$destroy', function () {
                    $interval.cancel(timeoutId);
                });

                // start the UI update process; save the timeoutId for canceling
                updateTime();
                timeoutId = $interval(function () {
                    updateTime(); // update DOM
                }, 1000);
            }

            return {
                link: link
            };
        }]);
})();