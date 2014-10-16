/**
 * Created by stanislav on 10/16/14.
 */
angular.module('pullr.streamboard.draggable', ['pullr.streamboard.regions']).
    directive('draggable', function ($document, regions) {
        return {
            link: function (scope, element, attr) {
                var startX = 0, startY = 0, x = 0, y = 0;
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
                    startX = event.screenX - x;
                    startY = event.screenY - y;

                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                function mousemove(event) {
                    y = event.screenY - startY;
                    x = event.screenX - startX;
                    element.css({
                        left: x + 'px',
                        top: y + 'px'
                    });
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