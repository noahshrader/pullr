angular.module('pullr.streamboard.interaction', ['pullr.streamboard.regions']).
    directive('interaction', function ($document, regions) {
        return {
            link: function (scope, element, attrs) {
                var $element = $(element);
                if (attrs.draggable != null) {
                    var $parent = $element.parents('.region:eq(0)');     
                    var fields = null;        
                    if (scope.hasOwnProperty('draggableConfig')) {
                        fields = scope.$eval(attrs.draggableFields);    
                        var left = scope.draggableWidget[fields['widgetLeftAttribute']];
                        var top = scope.draggableWidget[fields['widgetTopAttribute']];

                        if (left != 0 && top != 0) {                        
                            $element.css({
                                left : scope.draggableWidget[fields['widgetLeftAttribute']],
                                top  : scope.draggableWidget[fields['widgetTopAttribute']]
                            });    
                        }                    
                    }
                    
                    var defaultConfig = {                                        
                        stop: function(event, ui) {
                            if (scope.hasOwnProperty('draggableWidget') 
                                && scope.hasOwnProperty('draggableRegion')
                                && fields != null) {
                                scope.draggableWidget[fields['widgetLeftAttribute']] = ui.position.left;
                                scope.draggableWidget[fields['widgetTopAttribute']] = ui.position.top;
                                regions.regionChanged(scope.draggableRegion);
                            }                                                                 
                        }
                    };
                    var userConfig = {};
                    if (scope.hasOwnProperty('draggableConfig')) {
                        userConfig = scope.$eval(attrs.draggableConfig);
                    }
                    var config = jQuery.extend({}, defaultConfig, userConfig);
                    
                    $element.draggable(config);
                }

                if (attrs.resizable != null) {
                    console.log(scope.resizableRegion);
                    var defaultConfig = {
                        animate: false,
                        delay: 0,
                        stop: function(event, ui) {
                            if (scope.resizableCallback && scope.resizableRegion) {
                                scope.resizableCallback(scope.resizableRegion, event, ui);
                            }
                        }
                    };
                    var userConfig = {};
                    if (scope.hasOwnProperty('resizableConfig')) {
                        userConfig = scope.$eval(attrs.resizableConfig);
                        console.log(userConfig);
                    }
                    var config = $.extend({}, defaultConfig, userConfig);
                    
                    $element.resizable(config);

                    if (scope.hasOwnProperty('resizableSize')
                        && scope.resizableSize.width > 0
                        && scope.resizableSize.height > 0) {                                                

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
                resizableSize: '='
            }
        }
    })
;