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
                var $element = $(element);
                var duration = 8000;
                var scroll = true;
                var timer = null;


                function clearTimer() {
                    $element.show();
                    if(timer){
                        $interval.cancel(timer);
                    }
                }

                if (attrs.scroll === undefined) {                                        
                    timer = $interval(function () {
                        startMarquee()
                    }, 3000, 1);
                }
                
                if (attrs.scroll !== undefined) {
                    scope.$watch(attrs.scroll, function (value) {
                        console.log('scroll has been changed', value);                        
                        scroll = value;
                        clearTimer();
                        if (value) {     
                            $element.hide();                     
                            timer = $interval(function () {
                                startMarquee()
                            }, 3000, 1);
                        } else {
                            $element.marquee('destroy');
                            $element.show();
                        }
                    })
                }

                
                scope.$watch(attrs.duration, function(value){   
                    clearTimer();
                                       
                    switch (value) {
                        case 'Slow':
                            duration = 8000;
                            break;
                        case 'Normal':
                            duration = 6000;
                            break;
                        case 'Fast':
                            duration = 3000;
                            break;
                    }

                    if (scroll) {
                        $element.marquee('destroy');
                        $element.hide();
                        timer = $interval(function () {
                            startMarquee()
                        }, 3000, 1);
                    }
                })

                function startMarquee() {
                    $element.show();
                    $element.marquee({
                        gap: 0,
                        delayBeforeStart: 0,
                        pauseOnHover: true,
                        direction: 'left',
                        duplicated: false,
                        duration: duration
                    });
                }
            }

            return {
                link: link
            }
        });
})();