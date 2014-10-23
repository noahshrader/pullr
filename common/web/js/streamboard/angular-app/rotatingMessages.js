(function () {
    'use strict';
    angular.module('pullr.streamboard.rotatingMessages', [])
        .directive('rotatingMessages', function ($interval) {
            function link(scope, element, attrs) {
             
                var $messageContainer = $('#rotating-message-container');
                var isRunning = false,                
                    i = -1;
          
        
                var timer;
                var messagesModule;
        
                scope.$watch('messagesModule', function (value) {
                    messagesModule = value;              
                    if (messagesModule) {
                        if ( ! isRunning ) {
                            isRunning = true;
                            nextMessage();
                        }                        
                    } else {
                        isRunning = false;
                    } 
                    if ( ! isRunning ) {
                        $interval.cancel(timer);
                    }                   
                });

                scope.$watchCollection('[messagesModule.message1, messagesModule.message2, messagesModule.message3, messagesModule.message4, messagesModule.message5]', function(messages){
                    var allMessageIsEmpty = true;
                    for (var i = 0; i < messages.length; i++) {
                        if (messages[i].trim() != '') {
                            allMessageIsEmpty = false;
                            break;
                        }
                    }        
                    if (allMessageIsEmpty) {
                        isRunning = false;
                        console.log('[Rorating messages]: Empty message list. Stop');
                    } else if (messagesModule && ! isRunning) {
                        isRunning = true;
                        nextMessage();
                    }
                    if ( ! isRunning ) {
                        $interval.cancel(timer);
                    }    
                })

                

                element.on('$destroy', function () {
                    isRunning = false; 
                    $interval.cancel(timer);                                   
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
                    if (getMessage(i)) {                         
                        $messageContainer.html(getMessage(i));
                        $messageContainer.removeClass().addClass('animated fadeIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            timer = $interval(function(){
                                $messageContainer.removeClass().addClass('animated fadeOut');
                                if( isRunning ) {
                                    $messageContainer.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', nextMessage);                                
                                }  
                            }, scope.rotationSpeed * 1000, 1)                                          
                        });                        
                    }                                      
                }
            }

            return {
                scope: {
                    messagesModule : '=',
                    rotationSpeed : '='
                },
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
