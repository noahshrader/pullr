(function () {
    var app = angular.module('pullr.streamboard.regionsConfigs', ['pullr.common', 'pullr.streamboard.alertMediaManager', 'pullr.streamboard.campaigns',
        'pullr.streamboard.regions', 'angularFileUpload', 'pullr.streamboard.stream', 'ui.bootstrap.datetimepicker', 'pullr.streamboard.config']);
    app.run(['$rootScope', '$http', function ($rootScope, $http) {
        $rootScope.GOOGLE_FONTS = [];

        var ALLOWED_FONTS = ['Open Sans', 'Open Sans Condensed', 'Josefin Slab', 'Arvo', 'Lato', 'Merriweather',
            'Ubuntu', 'Droid Sans', 'Roboto', 'Raleway', 'Oswald', 'Montserrat', 'Lobster', 'Shadows Into Light', 'Dosis',
            'Indie Flower', 'Play', 'Pacifico', 'Quicksand', 'Ropa Sans', 'Permanent Marker', 'Ubuntu Condensed',
            'Francois One', 'PT Serif', 'Titillium Web'];
        for (var key in ALLOWED_FONTS) {
            ALLOWED_FONTS[key] = ALLOWED_FONTS[key].toUpperCase();
        }
        var fonts;
        if (fonts = localStorage.getItem('GOOGLE_FONTS')) {
            $rootScope.GOOGLE_FONTS = JSON.parse(fonts);
        }
        function requestFonts() {
            /*here is float bug can be (rare case, only for first load) - fontStyle directive doesn't rerender after update of GOOGLE_FONTS variable*/
            $http.get('https://www.googleapis.com/webfonts/v1/webfonts?key=' + $rootScope.Pullr.params.googleAPIKey).success(function (data) {
                var items = data.items;
                var fonts = [];
                for (var key in items) {
                    var item = items[key];
                    fonts.push({family: item.family, files: item.files});
                }
                fonts = limitFonts(fonts);
                localStorage.setItem('GOOGLE_FONTS', JSON.stringify(fonts));
                $rootScope.GOOGLE_FONTS = fonts;
            });
        }

        /*even if we use cache we request for fonts update from google*/
        requestFonts();
        function limitFonts(allFonts) {
            var fonts = [];
            var foundFamilies = [];
            for (var key in allFonts) {
                var font = allFonts[key];
                var family = font.family.toUpperCase();
                if (ALLOWED_FONTS.indexOf(family) > -1) {
                    fonts.push(font);
                    foundFamilies.push(family);
                }
            }
            var diff = $(ALLOWED_FONTS).not(foundFamilies).get();
            if (diff.length > 0) {
                console.log('[ERROR] Not found families:');
                console.log(diff);
            }
            return fonts;
        }
    }]);
    app.directive('fontStyle', ['$rootScope', '$http', '$timeout', function ($rootScope, $http, $timeout) {
        return {
            scope: {
                model: '=ngModel'
            },
            controller: function ($scope) {
                $scope.regionChanged = function () {
                    /**to have $parent scope updated we are waiting to the next $digest cycle*/
                    $timeout(function () {
                        var $parent = $scope.$parent;
                        var region = $parent.region;
                        $parent.regionChanged(region);
                    });
                }
            },
            templateUrl: 'angular/views/streamboard/region/fontStyle.html'
        }
    }]);

    app.controller('RegionConfigCtrl', ['$rootScope', '$scope', '$http', '$upload', 'campaigns', 'alertMediaManager', 'regions', 'stream', 'simpleMarqueeHelper', '$timeout', 'customDonationSound', 'streamboardConfig',
        function ($rootScope, $scope, $http, $upload, campaigns, alertMediaManager, regions, stream, simpleMarqueeHelper, $timeout, customDonationSound, streamboardConfig) {
            $scope.alertMediaManagerService = alertMediaManager;
            $scope.campaignsService = campaigns;
            $scope.streamService = stream;
            $scope.regionsService = regions;
            $scope.customDonationSound = customDonationSound;
            $scope.MAX_FONT_SIZE = 72;
            $scope.MIN_FONT_SIZE = 14;
            $scope.MIN_FONT_WEIGHT = 300;
            $scope.MAX_FONT_WEIGHT = 900;
            $scope.hideFooter = false;
            $scope.regionChanged = regions.regionChanged;
            $scope.streamboardConfig = streamboardConfig;
            $scope.toggleModule = function(region) {
                $scope.toggleFooter(region);
                regions.regionChanged(region);
            }

            $scope.toggleFooter = function(region) {

                if (region.widgetType == 'widget_alerts') {
                    var widget = region.widgetAlerts;
                    if (widget.includeDonations || widget.includeFollowers || widget.includeSubscribers) {
                        $scope.hideFooter = false;
                    } else {
                        $scope.hideFooter = true;
                    }
                } else if (region.widgetType == 'widget_campaign_bar') {
                    var widget = region.widgetCampaignBar;
                    if (widget.alertsEnable || widget.messagesEnable || widget.timerEnable) {
                        $scope.hideFooter = false;
                    } else {
                        $scope.hideFooter = true;
                    }
                } else {
                    $scope.hideFooter = true;
                }
            }

            $scope.$on('regionTabChanged', function(e, data) {
                $scope.toggleFooter(data.region);
            });

            var timmer = null;
            $scope.alertTextChange = function(region) {
                if(timmer !== null){
                    $timeout.cancel(timmer);
                }
                timmer = $timeout(function() {
                    console.log(region);
                    regions.regionChanged(region);
                }, 300);
            }

            $scope.changeAlertTextAlignment = function(alignment, preference, region) {
                preference.textAlignment = alignment;
                regions.regionChanged(region);
            }

            $scope.fontSizeChange = function(region) {
                simpleMarqueeHelper.recalculateMarquee();
                regions.regionChanged(region);
            }

            $scope.fontWeightChange = function(region) {
                simpleMarqueeHelper.recalculateMarquee();
                regions.regionChanged(region);
            }
            $scope.donationMessageChanged = function(region){
                simpleMarqueeHelper.recalculateMarquee();
                regions.regionChanged(region);
            }

            $scope.selectSound = function (preference, sound, soundType, region) {
                preference.sound = sound;
                preference.soundType = soundType;
                regions.regionChanged(region);
            };
            $scope.selectImage = function (preference, image, imageType, region) {
                preference.image = image;
                preference.imageType = imageType;
                regions.regionChanged(region);
            };
            $scope.selectCampaignAlertBackground = function(preference, image, region) {
                preference.background = image;
                regions.regionChanged(region);
            };

            $scope.campaignBackgroundChanged = function(background, region) {
                region.widgetCampaignBar.background = background;
                regions.regionChanged(region);
            };

            $scope.removeCampaignAlertBackground = function(image, region, event) {
                if (event) {
                    event.stopPropagation();
                    event.preventDefault();
                }
                console.log(image, region);
                region.widgetCampaignBar.alertsModule.background = '';
                alertMediaManager.removeCampaignAlertBackground(image);
                regions.regionChanged(region);
            }
            $scope.removeCampaignBackground = function(image, region, event) {
                if (event) {
                    event.stopPropagation();
                    event.preventDefault();
                }
                region.widgetCampaignBar.background = '';
                alertMediaManager.removeCampaignBackground(image);
                regions.regionChanged(region);
            }

            $scope.onSetTimeCountDownFrom = function (newDate, oldDate) {
                var scope = this.$parent;
                /*we need to update value first to sending to server*/
                scope.module.countDownFrom = newDate;
                $scope.regionChanged(scope.region);
            }
            $scope.onSetTimeCountDownTo = function (newDate, oldDate) {
                var scope = this.$parent;
                /*we need to update value first to sending to server*/
                scope.module.countDownTo = newDate;
                $scope.regionChanged(scope.region);
            }
            $scope.onFileUpload = function ($files, $fileType, scope) {
                var file = $files[0];
                $scope.upload = $upload.upload({
                    url: 'app/streamboard/upload_alert_file_ajax', //upload.php script, node.js route, or servlet url
                    //method: 'POST' or 'PUT',
                    //headers: {'header-key': 'header-value'},
                    //withCredentials: true,
                    data: {type: $fileType},
                    file: file // or list of files ($files) for html5 only
                    //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
                    // customize file formData name ('Content-Disposition'), server side file variable name.
                    //fileFormDataName: myFile, //or a list of names for multiple files (html5). Default is 'file'
                    // customize how data is added to formData. See #40#issuecomment-28612000 for sample code
                    //formDataAppender: function(formData, key, val){}
                }).progress(function (evt) {
                    console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
                }).success(function (data, status, headers, config) {
                    // file is uploaded successfully
                    switch ($fileType) {
                        case 'sound':
                            alertMediaManager.customSounds = data;
                            break;
                        case 'image':
                            alertMediaManager.customImages = data;
                            break;
                        case 'campaignBackground':
                            alertMediaManager.customCampaignBackgrounds = data;
                            break;
                        case 'campaignAlertBackground':
                            alertMediaManager.customCampaignAlertBackgrounds = data;
                            break;
                    }
                    scope.error = '';
                }).error(function (data) {
                    scope.error = data.message;
                    console.log(data);
                });
                //.then(success, error, progress);
                // access or attach event listeners to the underlying XMLHttpRequest.
                //.xhr(function(xhr){xhr.upload.addEventListener(...)})
            }

            $scope.timerCountUpStart = function (module, region) {
                var pauseDate = new Date(module.countUpPauseTime);
                var startDate = new Date(module.countUpStartTime);
                if (!startDate) {
                    module.countUpStartTime = new Date();
                } else if (pauseDate >= startDate) {
                    module.countUpStartTime = new Date(startDate.getTime() + (new Date() - pauseDate));
                }
                module.countUpStatus = true;
                $scope.regionChanged(region);
            }

            $scope.timerCountUpPause = function (module, region) {
                module.countUpPauseTime = new Date();
                module.countUpStatus = false;
                $scope.regionChanged(region);
            }
            $scope.timerCountUpReset = function (module, region) {
                module.countUpStartTime = new Date();
                module.countUpPauseTime = new Date();
                $scope.regionChanged(region);
            }
            // var conatinerId = 'region_' + $scope.region
            // var $donationChildScope = angular.element(".donations_panel").scope();
            // console.log($donationChildScope.customsounds);
            $scope.changeCustomSound = function(rangeData, key, module, region){
                for(var i in rangeData){
                    if(rangeData[i] == ""){
                        delete rangeData[i];
                    }
                }
                customDonationSound.customsounds[key] = rangeData;
                customDonationSound.rangeData[key] = customDonationSound.getRange(key);
                customDonationSound.showRangeTab[key] = (customDonationSound.rangeData[key].length>0);
                var list = [];
                for(var i in rangeData){
                    if(rangeData[i] != ""){
                        list.push({userId:region.userId, regionNumber:region.regionNumber, fileName:i, donationAmount:rangeData[i]});
                    }
                }

                if(module.preferenceType == "donations"){
                    region.widgetAlerts.donationCustomsound = list;
                }else if(module.preferenceType == "campaigns"){
                    module.donationCustomsound = list;
                }
                $scope.regionChanged(region);
                return true;
            }
        }]);
})()
