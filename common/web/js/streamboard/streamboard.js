$(window).load(function() {

    // streamboard loader
    $(".spinner-wrap").addClass('powered').fadeOut();

    $(".donation-stream-scroll").resizable({
        handles: "w",
        minWidth: 100,
        animate: false,
        delay: 0
    });
    
    // resizing magic
    $("#sidepanel").resizable({
        handles: "w",
        minWidth: 250,
        animate: false,
        delay: 0,
        resize: function( event, ui ) {
            $("#sidepanel").css('left', 'auto');
        }
    });

    

    function initMarqueeContainer(){
        $marqueeContainer = $(".marquee-container");
        $marqueeContainer.resizable({
            handles: "all",
            minWidth: 350,
            minHeight: 100,
            animate: false,
            delay: 0          
        });

        $marqueeContainer.draggable();

        var $parent = $marqueeContainer.parents('.regionsContainer');
        var parentWidth = $parent.width();
        var sideWidth = $('#sidepanel').width();
        var marqueeWidth = parentWidth - sideWidth;

        $marqueeContainer.width(marqueeWidth);
    }

    initMarqueeContainer();
    $(".regionsContainer .region:first-child").resizable({
        handles: "s",
        animate: false,
        delay: 0,
        resize: function() {
            var remainingSpace = (100 * parseFloat($(this).css('height')) / parseFloat($(this).parent().css('height')));
            var divTwo = $(this).next();
            var divOneHeight = (remainingSpace) + '%';
            var divTwoHeight = (100 - remainingSpace) + '%';
            $(this).height(divOneHeight);
            $(divTwo).height(divTwoHeight);
        }
    });
    // resize fixed elements based on size of sidepanel
    $('#sidepanel').resize(function() {
        var panelhead = $('#sidepanel').width() - 30;
        var sidefooter = $('#sidepanel').width();
        $('.panel-head, .panel-title').width(panelhead);
        $('.right-side-footer, .overlay').width(sidefooter);
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
            scrollAmount: 8
        },
        scrollInertia: 200,
        callbacks:{
            whileScrolling: function(){
                $('.colorpicker').removeClass('colorpicker-visible');
            }
        },
        live: true
    });
    // resize iframe based on inner content
//    $('iframe').iFrameResize({
//        heightCalculationMethod: 'documentElementScroll'
//    });
});

$(function () {
    // panel toggles
    $(document).on('click', '.paneltoggle li a', function() {
        $(this).parent('li').toggleClass('active').siblings().removeClass('active');
        $('.'+$(this).data('panel')+'_panel').toggleClass('selected').siblings().removeClass('selected');
   	});
    $('.sidepanel-head').click(function(){ 
        $('.paneltoggle li').removeClass('active');
        $('.slidepanel').removeClass('selected');
    });
    
    // if panel is exposed, blur items in back
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
// google fonts
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