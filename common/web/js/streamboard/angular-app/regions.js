(function () {
    var app = angular.module('pullr.streamboard.regions', ['pullr.common', 'pullr.streamboard.alertMediaManager',
        'angular-bootstrap-select', 'angular-bootstrap-select.extra', 'pullr.streamboard.campaigns',
        'angularFileUpload']);
    app.run(function ($rootScope, $http) {
        $rootScope.GOOGLE_FONTS = [];

        var ALLOWED_FONTS = ['Open Sans', 'Open Sans Condensed', 'Josefin Slab', 'Arvo', 'Lato', 'Merriweather',
            'Ubuntu', 'Droid Sans', 'Roboto', 'Raleway', 'Oswald', 'Montserrat', 'Lobster', 'Shadows Into Light', 'Dosis',
            'Indie Flower', 'Play', 'Pacifico', 'Quicksand', 'Ropa Sans', 'Permanent Marker', 'Ubuntu Condensed',
            'Francois One', 'PT Serif', 'Titillium Web'];
        for (var key in ALLOWED_FONTS){
            ALLOWED_FONTS[key] = ALLOWED_FONTS[key].toUpperCase();
        }
        var fonts;
        if (fonts = localStorage.getItem('GOOGLE_FONTS')) {
            $rootScope.GOOGLE_FONTS = JSON.parse(fonts);
        }
        function requestFonts(){
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
        function limitFonts(allFonts){
           var fonts = [];
           var foundFamilies = [];
           for (var key in allFonts){
               var font = allFonts[key];
               var family = font.family.toUpperCase();
               if (ALLOWED_FONTS.indexOf(family) > -1){
                   fonts.push(font);
                   foundFamilies.push(family);
               }
           }
           var diff = $(ALLOWED_FONTS).not(foundFamilies).get();
           if (diff.length > 0){
               console.log('[ERROR] Not found families:');
               console.log(diff);
           }
           return fonts;
        }
    });
    app.directive('isolatedScope', function () {
        return {
            scope: true
        }
    });
    app.directive('fontStyle', function ($rootScope, $http, $timeout) {
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
    });
    app.controller('RegionCtrl', function ($rootScope, $scope, $http, $upload, campaigns, alertMediaManager) {
        $scope.alertMediaManagerService = alertMediaManager;
        $scope.campaignsService = campaigns;

        $scope.regions = {};
        $scope.MAX_FONT_SIZE = 72;
        $scope.MIN_FONT_SIZE = 10;
        $http.get('app/streamboard/get_regions_ajax').success(function (data) {
            $scope.regions = data;
        });
        $scope.regionChanged = function (region) {
            $http.post('app/streamboard/update_region_ajax', region);
        };
        $scope.selectSound = function (preference,sound, region) {
            preference.sound = sound;
            $scope.regionChanged(region);
        };
        $scope.playSound = function (sound) {
            var path = alertMediaManager.PATH_TO_LIBRARY_SOUNDS+sound;
            /*we are using $rootScope.audio to have ability to stop current audio if it is playing now*/
            if ($rootScope.audio){
                $rootScope.audio.pause();
            }

            $rootScope.audio = new Audio(path);
            $rootScope.audio.play();
        };
        $scope.onFileSelect = function($files) {
            //$files: an array of files selected, each file has name, size, and type.
            for (var i = 0; i < $files.length; i++) {
                var file = $files[i];
                $scope.upload = $upload.upload({
                    url: 'app/streamboard/upload_alert_file_ajax?type=sound', //upload.php script, node.js route, or servlet url
                    //method: 'POST' or 'PUT',
                    //headers: {'header-key': 'header-value'},
                    //withCredentials: true,
//                   data: {myObj: $scope.myModelObj},
                    file: file, // or list of files ($files) for html5 only
                    //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
                    // customize file formData name ('Content-Disposition'), server side file variable name.
                    //fileFormDataName: myFile, //or a list of names for multiple files (html5). Default is 'file'
                    // customize how data is added to formData. See #40#issuecomment-28612000 for sample code
                    //formDataAppender: function(formData, key, val){}
                }).progress(function(evt) {
                    console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
                }).success(function(data, status, headers, config) {
                    // file is uploaded successfully
                    alertMediaManager.customSounds = data;
                    $scope.soundUploadError = '';
                }).error(function(data){
                    $scope.soundUploadError = data.message;
                    console.log(data);
                });
                //.error(...)
                //.then(success, error, progress);
                // access or attach event listeners to the underlying XMLHttpRequest.
                //.xhr(function(xhr){xhr.upload.addEventListener(...)})
            }
        }
    });
})()