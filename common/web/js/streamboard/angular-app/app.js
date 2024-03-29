(function () {
    var app = angular.module('streamboardApp', ['vr.directives.slider', 'ui.select2', 'ui.bootstrap','angularMoment',
        'pullr.streamboard.interaction',
        'pullr.streamboard.donationsCtrl', 'pullr.streamboard.regionsPanels','pullr.streamboard.regionsConfigs',
        'pullr.streamboard.settings', 'pullr.streamboard.stream', 'colorpicker.module',
        'pullr.streamboard.rotatingMessages', 'pullr.currentTime','pullr.countUpTimer','simpleMarquee',
        'pullr.streamboard.twitch','pullr.streamboard.config']);
    app.run(function(){
    	$(".spinner-wrap").addClass('powered').fadeOut();
    });

})();