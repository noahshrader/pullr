angular.module('pullr.streamboard.resizable', []).
    directive('resizable', function ($document, regions) {
        return {
            link: function (scope, element, attr) {
                var options = JSON.parse(attr.resizable);

                options.stop = function (event, ui){
                    console.log(ui.size);
                    regions.regionChanged(scope.region);
                };

                if (options.minHeight) {
                    $(element).css({minHeight: options.minHeight});
                }

                if (options.minWidth) {
                    $(element).css({minWidth: options.minWidth});
                }

                if (options.defaultWidth) {
                    $(element).css({width: options.defaultWidth});
                }

                $(element).resizable(options);
            }
        }
    })
;
