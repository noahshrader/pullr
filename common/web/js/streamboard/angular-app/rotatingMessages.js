(function () {
    'use strict';
    angular.module('pullr.streamboard.rotatingMessages', [])
        .directive('rotatingMessages', function ($interval) {
            function link(scope, element, attrs) {
                var messagesModule = null,
                    i = -1,
                    intervalId = null,
                    rotationSpeed = 5;

                scope.$watch(attrs.rotationSpeed, function (value) {
                    rotationSpeed = value;
                });
                scope.$watch(attrs.messagesModule, function (value) {
                    messagesModule = value;
                    if (!intervalId) {
                        nextMessage();
                    }
                });

                element.on('$destroy', function () {
                    $interval.cancel(intervalId);
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
                        $(element).text(getMessage(i));
                    }
                    intervalId = $interval(nextMessage, rotationSpeed * 1000, 1);
                }
            }

            return {
                link: link
            }
        });
})();
