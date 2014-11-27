angular.module('pullr.streamboard.interaction', ['pullr.streamboard.regions']).
directive('interaction', ['$document', 'regions', function ($document, regions) {
    return {
        link: function (scope, element, attrs) {
            var $element = $(element);
            if (attrs.draggable != null) {
                var $parent = $element.parents('.region:eq(0)');     
                        
                if (scope.draggableFields) {
                
                    var left = scope.draggableWidget[scope.draggableFields.widgetLeftAttribute];
                    var top = scope.draggableWidget[scope.draggableFields.widgetTopAttribute];

                    if (left != 0 || top != 0) {                        
                        $element.css({
                            left : scope.draggableWidget[scope.draggableFields.widgetLeftAttribute],
                            top  : scope.draggableWidget[scope.draggableFields.widgetTopAttribute]
                        });    
                    }                    
                }
                
                var defaultConfig = {   
                    scroll: false,                                     
                    stop: function(event, ui) {
                        if (scope.hasOwnProperty('draggableWidget') 
                            && scope.hasOwnProperty('draggableRegion')) {                                
                            scope.draggableWidget[scope.draggableFields.widgetLeftAttribute] = ui.position.left;
                            scope.draggableWidget[scope.draggableFields.widgetTopAttribute] = ui.position.top;
                            regions.regionChanged(scope.draggableRegion);
                        }                                                                 
                    }
                };
                
                var config = jQuery.extend({}, defaultConfig, scope.draggableConfig);                    
                $element.draggable(config);
            }

            if (attrs.resizable != null) {                    
                var defaultConfig = {
                    animate: false,
                    delay: 0,
                    stop: function(event, ui) {                           
                        if (scope.resizableCallback) {
                            if (scope.resizableRegion) {
                                scope.resizableCallback(scope.resizableRegion, event, ui);
                            } else {
                                scope.resizableCallback(event, ui);
                            }                                
                        }
                    }
                };                                        
                var config = $.extend({}, defaultConfig, scope.resizableConfig);                                      
                if ( ! attrs.hasOwnProperty('resizableCondition') || (attrs.hasOwnProperty('resizableCondition') && scope.resizableCondition == true)) {
                    $element.resizable(config);    
                }                    
                

                if (scope.resizableSize
                    && scope.resizableSize.width > 0
                    && scope.resizableSize.height > 0
                    && scope.resizableSize.width != null
                    && scope.resizableSize.height != null) {                                                

                    $element.css({
                        width: scope.resizableSize.width,
                        height: scope.resizableSize.height
                    });
                }
            }
        },
        scope: {
            draggableRegion:'=',
            draggableWidget:'=',
            draggableFields:'=',
            draggableConfig:'=',
            resizableConfig: '=',
            resizableCallback: '=',
            resizableRegion: '=',
            resizableSize: '=',
            resizableCondition: '='
        }
    }
}]);