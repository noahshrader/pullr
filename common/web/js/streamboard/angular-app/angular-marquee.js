(function () {
    'use strict';
    /**
     * @name angularMarquee
     *
     * @description
     * angularMarquee module provides jquery-marquee functionality for angular.js apps.
     */
    angular.module('angularMarquee', [])
        .directive('angularMarquee', function ($interval) {
            function link(scope, element, attrs) {
                if (attrs.scroll === undefined) {
                    $interval(function () {
                        startMarquee()
                    }, 5000, 1);
                }
                if (attrs.scroll !== undefined) {
                    scope.$watch(attrs.scroll, function (value) {
                        console.log('scroll has been changed');
                        console.log(value);
                        if (value) {
                            $interval(function () {
                                startMarquee()
                            }, 5000, 1);
                        } else {
                            $(element).marquee('destroy');
                        }
                    })
                }

                function startMarquee() {
                    $(element).marquee({
//                        duration: 8000, // Slow = 28000; Normal = 16000; Fast = 8000;
                        gap: 100,
                        delayBeforeStart: 0,
                        pauseOnHover: true,
                        direction: 'left',
                        duplicated: true
                    });
                }
            }

            return {
                link: link
            }
        });
})();