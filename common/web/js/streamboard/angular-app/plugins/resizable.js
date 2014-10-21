angular.module('pullr.streamboard.resizable', []).
    directive('resizable', function ($document, regions) {
        return {
            link: function (scope, element, attr) {
                var options = JSON.parse(attr.resizable);

                options.stop = function (event, ui) {
                    scope.region[attr.resizablePath].width = ui.size.width;
                    scope.region[attr.resizablePath].height = ui.size.height;
                    regions.regionChanged(scope.region);
                };

                if (options.minHeight) {
                    $(element).css({minHeight: options.minHeight});
                }

                if (options.minWidth) {
                    $(element).css({minWidth: options.minWidth});
                }

                if (scope.region[attr.resizablePath]) {
                    $(element).css({
                        width: scope.region[attr.resizablePath].width,
                        height: scope.region[attr.resizablePath].height}
                    );
                }

                $(element).resizable(options);
            }
        }
    })
;
