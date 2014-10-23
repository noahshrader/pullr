(function () {
    'use strict';
    angular.module('pullr.streamboard.rotatingMessages', [])
        .directive('rotatingMessages', function ($interval) {
            function link(scope, element, attrs) {
             
                var $messageContainer = $('#rotating-message-container');
                var isRunning = false;
                var messagesModule = null,
                    i = -1,
          
                    rotationSpeed = 5;

                scope.$watch(attrs.rotationSpeed, function (value) {
                    rotationSpeed = value;
                });
                scope.$watch(attrs.messagesModule, function (value) {
                    messagesModule = value;
                    if ( ! isRunning ) {
                        isRunning = true;
                        nextMessage();
                    }
                });

                element.on('$destroy', function () {
                    isRunning = false;
                });      

                function getMessage(i) {
                    switch (i) {
                        case 0:
                            return messagesModule.message1;
                        case 1:
                            return messagesModule.message2;
                        case 2:
                            return messagesModule.message3;
                        case 3:
                            return messagesModule.message4;
                        case 4:
                            return messagesModule.message5;
                    }
                }


                function nextMessage() {
                    i = (i + 1) % 5;
                    var j = 0;
                    while ((!getMessage(i)) && j < 5) {
                        i = (i + 1) % 5;
                        j++;
                    }
                    console.log(getMessage(i));
                    if (getMessage(i)) {                        
                        $messageContainer.html(getMessage(i));
                        $messageContainer.removeClass().addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            $interval(function(){
                                $messageContainer.removeClass().addClass('animated fadeOut');
                                if( isRunning ) {
                                    $messageContainer.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', nextMessage);                                
                                }  
                            }, rotationSpeed * 1000, 1)                                          
                        });                        
                    }

                  
                    
                }
            }

            return {
                compile: function(tElement) {
                    var $element = $(tElement);
                    $element.append('<div id="rotating-message-container"></div>');
                    return {
                        post: link
                    }
                }                
            }
        });
})();
