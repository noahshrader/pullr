$(function () {
    $(".resizable").resizable({
        maxWidth: (screen.width/2),
        minWidth: 250,
        handles: "w",
        animate: false,
        delay: 0,
        resize: function( event, ui ) {
            $(".resizable").css('left', 'auto');
        }
    });
    
    // onload functions
    $(window).load(function() {
        // streamboard loader
        $(".spinner-wrap").fadeOut();
        // bottom menu appends
        $('#region_1 .right-side-footer').appendTo('#region_1');
        $('#region_2 .right-side-footer').appendTo('#region_2');
        // make source iframe adjust to height of inner content
        $('iframe').iFrameResize({
            heightCalculationMethod: 'documentElementScroll'
        });
    });

    // panel toggles
    $(document).on('click', '.paneltoggle li a', function() {
        $(this).parent('li').toggleClass('active').siblings().removeClass('active');
   		$('.'+$(this).data('panel')+'_panel').toggleClass('selected').siblings().removeClass('selected');
        if($('').hasClass('')) {
            $('.donations-list, .form-group').toggleClass('mute');
    }
   	});

    $('a.close').click(function() {
   		$(this).closest('div').toggleClass('selected');
   	});

    $(window).resize(function() {
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

function requireGoogleFont(fontFamily){
   if (!fontFamily){
       return;
   }
   console.log(fontFamily);
   WebFont.load({
       google: {
           families: [fontFamily]
        }
   });
}