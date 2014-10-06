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
                var duration = 3000;
                var scroll = true;
                if (attrs.scroll === undefined) {
                    $interval(function () {
                        startMarquee()
                    }, 5000, 1);
                }
                
                if (attrs.scroll !== undefined) {
                    scope.$watch(attrs.scroll, function (value) {                        
                        console.log('scroll has been changed', value);                        
                        scroll = value;
                        if (value) {

                            $interval(function () {
                                startMarquee()
                            }, 3000, 1);
                        } else {
                            $(element).marquee('destroy');
                        }
                    })
                }

                scope.$watch(attrs.duration, function(value){   
                    console.log(value);                 
                    $(element).marquee('destroy');
                    switch(value){
                        case 'Slow':
                            duration = 4500;
                            break;
                        case 'Normal':
                            duration = 3000;
                            break;
                        case 'Fast':
                            duration = 1500;
                            break;
                    }
                    if(scroll){
                        $(element).marquee('destroy');
                        $interval(function () {
                            startMarquee()
                        }, 3000, 1);
                    }
                })

                function startMarquee() {
                    $(element).marquee({
//                        duration: 8000, // Slow = 28000; Normal = 16000; Fast = 8000;
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