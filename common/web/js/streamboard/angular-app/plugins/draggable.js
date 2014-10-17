angular.module('pullr.streamboard.draggable', ['pullr.streamboard.regions']).
    directive('draggable', function ($document, regions) {
        return {
            link: function (scope, element, attr) {
                var startX = 0, startY = 0, x = 0, y = 0;
                /**edget is 10% of an element to use with resizable plugin*/
                var EDGE = 0.98;

                if (scope.positionContainer) {
                    x = scope.positionContainer.positionX;
                    y = scope.positionContainer.positionY;
                    element.css({
                        left: x + 'px',
                        top: y + 'px'
                    });
                } else {
                    console.log('Position container is not available');
                }
                element.css({
                    position: 'relative',
                    cursor: 'pointer',
                    display: 'block'
                });
                element.on('mousedown', function (event) {
                    // Prevent default dragging of selected content
                    event.preventDefault();
                    var offset =  $(element).offset();
                    if (attr.resizable && ( ( (event.pageX - offset.left) / $(element).width()) >= EDGE || ( (event.pageY - offset.top) / $(element).height()) >= EDGE )) {
                        return;
                    }
                    startX = event.pageX - x;
                    startY = event.pageY - y;
                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                function mousemove(event) {
                    y = validY(event.pageY - startY);
                    x = validX(event.pageX - startX);
                    element.css({
                        left: x + 'px',
                        top: y + 'px'
                    });
                }


                function validX(x) {
                    var parentWidth = $(element).parent().width();
                    var width = $(element).width();
                    var maxX = Math.max(0, parentWidth - width);
                    return Math.min(Math.max(x, 0), maxX);
                }

                function validY(y) {
                    var parentHeight = $(element).parent().height();
                    var height = $(element).height();
                    var maxY = Math.max(0, parentHeight - height);
                    return Math.min(Math.max(y, 0), maxY);
                }

                function mouseup() {
                    $document.off('mousemove', mousemove);
                    $document.off('mouseup', mouseup);

                    scope.positionContainer.positionX = x;
                    scope.positionContainer.positionY = y;
                    regions.regionChanged(scope.region);
                }
            },
            scope: {
                positionContainer: '=positionContainer',
                region: '=region'
            }
        }
    })
;