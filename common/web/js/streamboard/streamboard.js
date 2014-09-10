$(function () {
    $(".resizable-h").resizable({
        minWidth: 250,
        handles: "w",
        animate: false,
        delay: 0,
        alsoResize: ".right-side-footer",
        resize: function( event, ui ) {
            $(".resizable-h").css('left', 'auto');
        }
    });
    
    // onload functions
    $(window).load(function() {
        // streamboard loader
        $(".spinner-wrap").addClass('powered').fadeOut();
        // make source iframe adjust to height of inner content
        $('iframe').iFrameResize({
            heightCalculationMethod: 'documentElementScroll'
        });
        // Make items movable
        $(".movable").draggable({
            containment: "parent"
        });
        // custom scrollbars
        $(".pane").mCustomScrollbar({
            theme:"minimal",
            mouseWheel:{
                preventDefault: true,
                scrollAmount: 10
            },
            scrollInertia: 80,
        });
    });

    // panel toggles
    $(document).on('click', '.paneltoggle li a', function() {
        $(this).parent('li').toggleClass('active').siblings().removeClass('active');
   		$('.'+$(this).data('panel')+'_panel').toggleClass('selected').siblings().removeClass('selected');
   	});
    $('.regionsContainer, .sidepanel-head').click(function(){ 
        $('.paneltoggle li').removeClass('active');
        $('.slidepanel').removeClass('selected');
    });
    
    // If panel is exposed, blur items in back
    $(document).on('click', function() {
        var dimmed = $('.settings-wrap .form-group, .donations-list');
        if ($(".slidepanel").hasClass("selected")) {
            $(dimmed).addClass('dim');
        } else {
            $(dimmed).removeClass('dim');
        }
    });

    // toggle close right sidebar
    $("a.sidetoggle").click(function(){
        var l = $(this).data('l');
        var width = $('#sidepanel').width();
        $("#sidepanel, .right-side-footer").animate({right: (l ?  0 : -width)}, 200);
        $(this).data('l', !l);
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
   WebFont.load({
       google: {
           families: [fontFamily]
        }
   });
}