(function () {
    var app = angular.module('pullr.streamboard.donationsCtrl', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations']);
    app.controller('DonationsCtrl', function($scope, $http, campaigns, donations) {
        $scope.campaignsService = campaigns;
        $scope.donationsService = donations;
        
        $scope.nameHiddenToggle = function(donation){
            donation.streamboard.nameHidden = !donation.streamboard.nameHidden;
            donation.displayName = donation.streamboard.nameHidden || !donation.nameFromForm ? Pullr.ANONYMOUS_NAME : donation.nameFromForm;
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'nameHidden', value: donation.streamboard.nameHidden }).success(function(){
                /*let's update name for all donations*/
                donations.updateDonations(true);
            });
        }

        $scope.markAsRead = function(donation){
            var wasRead = true;
            if ( donation.streamboard.wasRead == true ) {
                wasRead = false;
            } else {
                wasRead = true;
            }            
            donation.streamboard.wasRead = wasRead;            
            $http.post('app/streamboard/set_donation_streamboard', {id: donation.id, property: 'wasRead', value: wasRead });
        }
        
        $scope.onResizeSidebar = function(event, ui) {
             $("#sidepanel").css('left', 'auto');
             $http.post('app/streamboard/set_streamboard_sidepanel_width', {
                width: ui.size.width
             });
        }

    });
})();
