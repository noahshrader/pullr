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
        service('alertMediaManager', function ($http) {
            function addUserToPath(path){
                return path + Pullr.user.id + '/';
            }
            var Service = this;
            $.extend(this, Pullr.Streamboard.AlertMediaManager);
            this.playSound = function (sound,soundType, volume) {
                if (!sound || !soundType){
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
                    default:
                        throw new Exception('Wrong type for playSound method');
                }
                /*we are using $rootScope.audio to have ability to stop current audio if it is playing now*/
                if (this.audio){
                    this.audio.pause();
                }

//                console.log('[VOLUME]');
//                console.log(volume/100)
//                console.log('[PATH]');
//                console.log(path);
                this.audio = new Audio(path);
                this.audio.volume = volume / 100;
                this.audio.play();
            };
            this.getImageUrl = function(image, imageType){
                if (!image || !imageType){
                    return null;
                }
                switch (imageType){
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
        });
})();