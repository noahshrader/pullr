angular.module('streamboardApp').controller('sideBarCtrl', ['$scope', '$http', 'streamboardConfig', 'regions',
function($scope, $http, streamboardConfig, regions) {
    $scope.regionsService = regions;
    $scope.streamboardConfig = streamboardConfig;
    //load sidebard witdh
    $scope.$watch('streamboardConfig.config.sidePanelWidth', function(width){                    
        if (width > 0) {
            $('#sidepanel').css({
                position:'absolute',
                width: width,
                left: 'auto',
                top:'0'
            });
            setSideBarWidth(width);
        }
    });
    
    $scope.onResizeSidebarStop = function(event, ui) {
        $("#sidepanel").css('left', 'auto');
        $http.post('app/streamboard/set_streamboard_sidepanel_width', {
            width: ui.size.width
        });
    }

    $('#sidepanel').resize(function() {
        $("#sidepanel").css('left', 'auto');
        var width = $('#sidepanel').width();
        setSideBarWidth(width);
    });

    function setSideBarWidth(width) {
        $("#sidepanel").css('left', 'auto');
        var panelhead = width - 40;
        var sidefooter = width;
        $('.panel-head, .panel-title').width(panelhead);
        $('.right-side-footer, .overlay, .veil').width(sidefooter);
    }

    $scope.regionTabChanged = function(region) {
        $scope.$broadcast ('regionTabChanged', {
            region: region
        });
    }
}]);