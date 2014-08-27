(function () {
    var app = angular.module('pullr.streamboard.regions', []).
        service('regions', function ($http) {
            var Service = this;
            this.regions = {};
            $http.get('app/streamboard/get_regions_ajax').success(function (data) {
                Service.regions = data;
                while (Service.__readyQueue.length > 0) {
                    var callback = Service.__readyQueue.shift();
                    callback();
                }
            });

            this.__readyQueue = [];
            /*you can subscribe to be sure when regions are ready*/
            this.ready = function (callback) {
                if (this.regions.length > 0) {
                    callback();
                } else {
                    this.__readyQueue.push(callback);
                }
            }
        });
})();
