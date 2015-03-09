(function(){
    var app = angular.module('pullr.streamboard.settings', ['pullr.common', 'pullr.streamboard.campaigns', 'pullr.streamboard.donations', 'simpleMarquee', 'pullr.streamboard.config']);
    app.controller('SettingsCtrl', ['$scope','campaigns', 'stream', 'donations', '$http', '$interval', 'simpleMarqueeHelper', 'streamboardConfig',
        function ($scope,campaigns, stream, donations, $http, $interval, simpleMarqueeHelper, streamboardConfig){
            $scope.campaignsService = campaigns;
            $scope.streamService = stream;
            $scope.streamboardConfigService = streamboardConfig;
            $scope.clearButton = function () {
                donations.clear();
                $http.post('app/streamboard/clear_button_ajax');
            };

            var lastSourceHeight = 0;
            var frame = angular.element('#frame');

            $interval(function(){
                var height = angular.element('body', frame.contents()).height();
                if (height > 0) {
                    frame.height(height);
                    lastSourceHeight = height;
                }
            }, 1000, 60);

            $scope.toggleSubscriber = function() {
                stream.toggleSubscriber();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.toggleFollower = function() {
                stream.toggleFollower();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.toggleGroupUser = function() {
                stream.toggleGroupUser();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.groupBasedChanged = function() {
                stream.groupBasedChanged();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.noActivityMessageChanged = function() {
                stream.noActivityMessageChanged();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.sortByChange = function(region) {
                stream.sortByChange();
                simpleMarqueeHelper.recalculateMarquee();
            }

            $scope.recalculateMarquee = function() {
                simpleMarqueeHelper.recalculateMarquee();
            }

        }]);
})()
