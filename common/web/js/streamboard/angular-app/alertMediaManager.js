(function () {
    var app = angular.module('pullr.streamboard.alertMediaManager', []).
    /**
     * List of service's properties:
     *  @param PATH_TO_LIBRARY_SOUNDS
     *  @param PATH_TO_LIBRARY_IMAGES
     *  @param PATH_TO_CUSTOM_SOUNDS
     *  @param PATH_TO_CUSTOM_IMAGES
     *  @param librarySounds - list of library's sounds
     *  @param libraryImages - list of library's images
     *  @param customSounds - list of custom sounds
     *  @param customImages - list of custom images
     */
        service('alertMediaManager', ['$http', '$filter', function ($http, $filter) {
            function addUserToPath(path){
                return path + Pullr.user.id + '/';
            }
            var Service = this;
            $.extend(this, Pullr.Streamboard.AlertMediaManager);
            var soundCache = {};
            var flashSupport = (navigator.mimeTypes && navigator.mimeTypes.length
                ? Array.prototype.slice.call(navigator.mimeTypes).some(function(a) {
                    return "application/x-shockwave-flash" == a.type;
                }) : /MSIE/.test(navigator.userAgent)
                ? eval("try { new ActiveXObject('ShockwaveFlash.ShockwaveFlash') && !0 } catch(e) { !1 };") : !1);

            var html5AudioSupport = (function() {
                                        var a = document.createElement('audio');
                                        return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
                                    })();

            this.init = function() {
                soundManager.setup({
                    preferFlash: !html5AudioSupport,
                    url: Pullr.streamboard_common_path + '/bower_components/soundmanager/swf',
                    onready: function() {
                        Service.preloadSound();
                    }
                });

                Service.preloadImage();
            }

            this.preloadImage = function() {
                for (var i  =0, il = Service.customImages.length; i < il; i++) {
                    var url = Service.getImageUrl(Service.customImages[i], 'Custom');
                    var img = new Image();
                    img.src = url;
                }

                for (var i = 0, il = Service.libraryImages.length; i < il; i++) {
                    var url = Service.getImageUrl(Service.libraryImages[i], 'Library');
                    var img = new Image();
                    img.src = url;
                }

                for (var i =0, il = Service.customCampaignBackgrounds.length; i < il; i++) {
                    var url = Service.getCampaignBackgroundUrl(Service.customCampaignBackgrounds[i]);
                    var img = new Image();
                    img.src = url;
                }
            }

            this.preloadSound = function() {
                for (var i = 0, il = Service.customSounds.length; i < il; i++) {
                    var sound = Service.customSounds[i];
                    var path = this.getSoundPath(sound, 'Custom');


                    soundCache[path] = soundManager.createSound({
                        url: path
                    });

                }

                for (var i = 0, il = Service.librarySounds.length; i < il; i++) {
                    var sound = Service.librarySounds[i];
                    var path = this.getSoundPath(sound, 'Library');
                    console.log(path)
                    soundCache[path] = soundManager.createSound({
                        url: path
                    });
                    console.log(soundCache[path]);
                }

            }

            this.getSoundPath = function(sound, soundType) {
                if (!sound){
                    return;
                }
                var path;
                switch (soundType){
                    case 'Library':
                        path = Service.PATH_TO_LIBRARY_SOUNDS+sound;
                        break;
                    case 'Custom':
                        path = addUserToPath(Service.PATH_TO_CUSTOM_SOUNDS)+sound;
                        break;
                }
                return path;
            }

            this.getSound = function(path) {
                if ( ! soundCache.hasOwnProperty(path)) {
                    soundCache[path] = soundManager.createSound({
                        url: path
                    });
                }
                return soundCache[path];
            }

            this.playSound = function (sound,soundType, volume) {

                if (!sound) {
                    return;
                }
                // var path;
                switch (soundType) {
                    case 'Library':
                        path = Service.PATH_TO_LIBRARY_SOUNDS+sound;
                        break;
                    case 'Custom':
                        path = addUserToPath(Service.PATH_TO_CUSTOM_SOUNDS)+sound;
                        break;
                    default:
                        var a = $filter('filter')(Service.customSounds, sound, true);
                        if(a.length > 0){
                            Service.playSound(a[0], 'Custom', volume);
                        }
                        var a = $filter('filter')(Service.librarySounds, sound, true);
                        if(a.length > 0){
                            Service.playSound(a[0], 'Library', volume);
                        }
                        return true;
                }

                var path = this.getSoundPath(sound, soundType);
                sound = this.getSound(path);
                if (sound) {
                    sound.setVolume(volume);
                    sound.play();
                }


            };
            this.getImageUrl = function(image, imageType) {
                if ( ! image || ! imageType) {
                    return null;
                }
                switch (imageType) {
                    case 'Library':
                        path = Service.PATH_TO_LIBRARY_IMAGES+image;
                        break;
                    case 'Custom':
                        path = addUserToPath(Service.PATH_TO_CUSTOM_IMAGES)+image;
                        break;
                    default:
                        throw new Exception('Wrong type for getImageUrl method');
                }
                return path;
            }

            this.getCampaignBackgroundUrl = function(image){
                if (!image) {
                    return null;
                } else {
                    return addUserToPath(Service.PATH_TO_CUSTOM_CAMPAIGN_BACKGROUNDS) + image;
                }
            }

            this.removeCampaignBackground = function(image){
                $http.post('app/streamboard/alert_remove_campaign_background_ajax', image).success(function(data){
                    Service.customCampaignBackgrounds = data;
                });
            }
            this.removeSound = function(sound){
                $http.post('app/streamboard/alert_remove_sound_ajax', sound).success(function(data){
                    Service.customSounds = data;
                });
            }
            this.removeImage = function(image){
                $http.post('app/streamboard/alert_remove_image_ajax', image).success(function(data){
                    Service.customImages = data;
                });
            }
            this.init();
        }]);
})();
