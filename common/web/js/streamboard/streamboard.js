$(function () {
    $(".resizable").resizable({
        maxWidth: (screen.width/2),
        minWidth: 287,
        handles: "w",
        animate: false,
        delay: 0,
        resize: function( event, ui ) {
            $(".resizable").css('left', 'auto');
        }
    });
    // streamboard loader
    $(window).load(function() {
        $(".spinner-wrap").fadeOut();
    });

    if (Pullr.ENV == 'dev'){
        $(".spinner-wrap").fadeOut();
    }
    // panel toggles
    $(document).on('click', '.paneltoggle li a', function() {
   		$('.'+$(this).data('panel')+'_panel').toggleClass('selected');
        $('.donations-list, .form-group').toggleClass('mute');
   	});

    $('a.close').click(function() {
   		$(this).closest('div').toggleClass('selected');
   	});

    // make source iframe adjsut to height of inner content
    $('iframe').iFrameResize({
        heightCalculationMethod: 'documentElementScroll'
    });

    $( window ).resize(function() {
        var width = $(window).width();
        var height = $(window).height();
        $.post('app/streamboard/set_streamboard_window', {width: width, height: height});
        /*parent window can be closed*/
        if (window.opener){
            window.opener.Pullr.Streamboard.streamboardWidth = width;
            window.opener.Pullr.Streamboard.streamboardHeight = height;
        }
    });

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        /*workaround for known bug for angular-slider, when shown from hidden refreshSlider event should be issued*/
        $('slider').parent().each(function(index, element){
            var $scope = angular.element(element).scope();
            $scope.$broadcast('refreshSlider');
        });
    });
    var currentStreamboardLeft = window.screenX;
    var currentStreamboardTop = window.screenY;
    setInterval(function(){
        if ( (window.screenX != currentStreamboardLeft ) || (window.screenY != currentStreamboardTop)){
            currentStreamboardLeft = window.screenX;
            currentStreamboardTop = window.screenY;
            $.post('app/streamboard/set_streamboard_window_position', {left: currentStreamboardLeft, top: currentStreamboardTop});
            /*parent window can be closed*/
            if (window.opener){
                window.opener.Pullr.Streamboard.streamboardLeft = currentStreamboardLeft;
                window.opener.Pullr.Streamboard.streamboardTop =  currentStreamboardTop;
            }
        }
    }, 1000)
});