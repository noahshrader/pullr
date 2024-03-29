<?php
namespace common\assets\streamboard;

use yii\web\AssetBundle;

/**
 * Class StreamboardCommonAsset used also at source page.
 * @package common\assets\streamboard
 */
class StreamboardSingleRegionAsset extends AssetBundle
{
    public $sourcePath = '@common/web';
    public $baseUrl = '@web';
    public $css = [
        'css/animate.css',
        'css/slide-animate.css',
    ];
    public $js = [
        '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js',
        'bower_components/venturocket-angular-slider/build/angular-slider.min.js',
        'bower_components/select2/select2.min.js',
        'bower_components/angular-ui-select2/src/select2.js',
        'bower_components/ng-file-upload/angular-file-upload.min.js',
        'bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js',
        'bower_components/angular-bootstrap/ui-bootstrap.min.js',
        'bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
        'bower_components/angular-timer/app/js/timer.js',
        'bower_components/moment/moment.js',
        'bower_components/angular-moment/angular-moment.min.js',
        'bower_components/angular-bootstrap-datetimepicker/src/js/datetimepicker.js',
        'bower_components/zeroclipboard/dist/ZeroClipboard.min.js',
        'bower_components/sprintf/dist/sprintf.min.js',
        'js/streamboard/angular-app/simple-marquee.js',
        'js/streamboard/angular-app/region/regions.js',
        /* angular-app begin */
        'js/streamboard/angular-app/plugins/interaction.js',
        'js/streamboard/angular-app/timers/current-time.js',
        'js/streamboard/angular-app/timers/count-up-timer.js',
        'js/streamboard/angular-app/donationsService.js',
        'js/streamboard/angular-app/rotatingMessages.js',
        'js/streamboard/angular-app/donationsCtrl.js',
        'js/streamboard/angular-app/stream.js',
        'js/streamboard/angular-app/regionsService.js',
        'js/streamboard/angular-app/regionsPanels.js',
        'js/streamboard/angular-app/regionsConfigs.js',
        'js/streamboard/angular-app/activityFeed.js',
        'js/streamboard/angular-app/activityFeedHelper.js',
        'js/streamboard/angular-app/pullr-common.js',
        'js/streamboard/angular-app/campaigns.js',
        'js/streamboard/angular-app/settings.js',
        'js/streamboard/angular-app/alertMediaManager.js',
        'js/streamboard/angular-app/app.js',
        'js/streamboard/angular-app/streamboardConfig.js',
        'js/streamboard/angular-app/sideBarCtrl.js',
        'js/streamboard/angular-app/twitch.js',
        'js/streamboard/angular-app/region/regionApp.js'
        /* angular-app end */
    ];
    public $depends = [
        'common\assets\streamboard\StreamboardCommonAsset',
    ];
}
