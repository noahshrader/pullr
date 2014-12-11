(function () {
    var app = angular.module('pullr.streamboard.regions', []).
        service('regions', ['$http', function ($http) {
            var Service = this;
            this.regions = {};
            this.__readyQueue = [];

            if (Pullr.Streamboard != undefined && Pullr.Streamboard.regionsData != undefined) {
                Service.regions = Pullr.Streamboard.regionsData;
                while (Service.__readyQueue.length > 0) {
                    var callback = Service.__readyQueue.shift();
                    callback();
                }
            } else {
                $http.get('app/streamboard/get_regions_ajax').success(function (data) {
                    Service.regions = data;
                    while (Service.__readyQueue.length > 0) {
                        var callback = Service.__readyQueue.shift();
                        callback();
                    }
                });
            }

            /*you can subscribe to be sure when regions are ready*/
            this.ready = function (callback) {
                if (this.regions.length > 0) {
                    callback();
                } else {
                    this.__readyQueue.push(callback);
                }
            }

            this.regionChanged = function (region) {
                $http.post('app/streamboard/update_region_ajax', region);
            };

        }])
        .service('customDonationSound', ['$rootScope', '$http', '$timeout', '$filter',
            function ($rootScope, $http, $timeout, $filter) {
                var Service = this;
                this.customsounds = {};
                this.rangeData = {};
                this.isInit = {};
                this.showRangeTab = {};
                this.init = function (value, key) {
                    var obj = {};
                    for (var i in value) {
                        var item = value[i];
                        if (item.donationAmount) {
                            obj[item.fileName] = item.donationAmount;
                        }
                    }
                    Service.customsounds[key] = obj;
                    Service.isInit[key] = true;
                };
                this.getRange = function (key) {
                    var rangedata = [];
                    // var sounddata = {};
                    for (var i in Service.customsounds[key]) {
                        if (Service.customsounds[key][i] == "") {
                            continue;
                        }
                        // sounddata['i'] = Service.customsounds.i;
                        var amounts = Service.customsounds[key][i].toString().split('-');
                        rangedata.push({
                            name: i,
                            fromValue: (isNaN(amounts[0]) ? 0 : parseFloat(amounts[0])),
                            endValue: (isNaN(amounts[1]) ? 0 : parseFloat(amounts[1]))
                        });
                    }
                    rangedata = $filter('orderBy')(rangedata, ["fromValue", "endValue"], false);
                    var list = [];
                    for (var i in rangedata) {
                        var item = rangedata[i];
                        var preitem = rangedata[i - 1];
                        if (item.fromValue == 0 && 0) {
                            continue;
                        }
                        if (i == 0 || (item.fromValue != preitem.fromValue || item.endValue != preitem.endValue)) {
                            list.push({
                                name: item.name,
                                amount: (item.endValue == 0 ? item.fromValue : item.fromValue + '-' + item.endValue)
                            })
                        }
                    }
                    return list;
                }

                this.getSoundFileByValue = function (amount, key) {
                    amount = isNaN(amount) ? 0 : parseFloat(amount);
                    var soundFile = null;
                    var minvalue = 0;
                    var maxvalue = 0;
                    for (var i in Service.customsounds[key]) {
                        if (Service.customsounds[key][i] == "") {
                            continue;
                        }
                        var amounts = Service.customsounds[key][i].toString().split('-');
                        var fromValue = isNaN(amounts[0]) ? 0 : parseFloat(amounts[0]);
                        var endValue = isNaN(amounts[1]) ? 0 : parseFloat(amounts[1]);

                        if (amount == fromValue && endValue == 0) {
                            return i;
                        }
                        if ((fromValue <= amount && endValue >= amount) && ((maxvalue - minvalue == 0 || endValue - fromValue < maxvalue - minvalue))) {
                            soundFile = i;
                            maxvalue = endValue;
                            maxvalue = fromValue;
                        }
                    }

                    return soundFile;
                }
            }]);
})();
