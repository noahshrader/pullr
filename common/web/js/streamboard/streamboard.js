$(function () {
    
    // onload functions
    $(window).load(function() {
        // streamboard loader
        $(".spinner-wrap").addClass('powered').fadeOut();
        // make source iframe adjust to height of inner content
        $('iframe').iFrameResize({
            heightCalculationMethod: 'documentElementScroll'
        });
        $("#sidepanel").resizable({
            minWidth: 250,
            handles: "w",
            animate: false,
            delay: 0,
            resize: function( event, ui ) {
                $("#sidepanel").css('left', 'auto');
            }
        });
        $(".regionsContainer .region:first-child").resizable({
            handles: "s",
            animate: false,
            delay: 0,
            resize: function( event, ui ) {
                var remainingSpace = $(this).parent().height() - $(this).outerHeight(true);
                var divTwo = $(this).next();
                var divTwoHeight = remainingSpace - (divTwo.outerHeight(true) - divTwo.height());
                divTwo.css('height', divTwoHeight + 'px');
            }
        });
        // resize fixed elements based on size of sidepanel
        $('#sidepanel').resize(function() {
            var panelhead = $('#sidepanel').width() - 30;
            var sidefooter = $('#sidepanel').width();
            $('.panel-head, .panel-title').width(panelhead);
            $('.right-side-footer').width(sidefooter);
        });
        // make items movable
        $(".movable").draggable({
            containment: "parent",
            scroll: false
        });
        // custom scrollbars
        $(".pane").mCustomScrollbar({
            theme:"minimal",
            mouseWheel:{
                preventDefault: true,
                scrollAmount: 10
            },
            scrollInertia: 80,
            callbacks:{
                alwaysTriggerOffsets: true,
                onTotalScrollBackOffset: 10,
                onScrollStart: function(){
                    $('.panel-head, .panel-title').addClass('border');
                },
                onTotalScrollBack: function(){
                    $('.panel-head, .panel-title').removeClass('border');
                }
            }
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
        var dimmed = $('.settings-wrap .module, .donations-list');
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
        $("#sidepanel, .right-side-footer, .panel-head, .panel-title").animate({right: (l ?  0 : -width)}, 200);
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
//function startMarquee(){
//    $('.donation-stream-scroll').marquee({
//        duration: 16000, // Slow = 28000; Normal = 16000; Fast = 8000;
//        gap: 50,
//        delayBeforeStart: 0,
//        direction: 'left',
//        duplicated: true
//    });
//}
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