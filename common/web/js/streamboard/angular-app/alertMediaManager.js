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
            this.playSound = function (sound,soundType, volume) {
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
                /*we are using $rootScope.audio to have ability to stop current audio if it is playing now*/
                if (this.audio){
                    this.audio.pause();
                }

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
        }]);
})();
