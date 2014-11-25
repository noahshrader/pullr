$(function () {
    // panel toggles
    $(document).on('click', '.paneltoggle li a', function() {
        $(this).parent('li').toggleClass('active').siblings().removeClass('active');
        $('.'+$(this).data('panel')+'_panel').toggleClass('selected').siblings().removeClass('selected');
        if($(this).parent('li').hasClass('active')) {
            $('.veil').fadeIn(200);
        } else {
            $('.veil').fadeOut(200);
        }
   	});
    $('.sidepanel-head').click(function(){ 
        $('.paneltoggle li').removeClass('active');
        $('.slidepanel').removeClass('selected');
        $('.veil').hide();
    });
    
    // collapsable containers
    $(document).on('click', '.module a.settingtoggle', function() {
        $(this).parent().toggleClass("show");
        angular.element(".tab-content").scope().$broadcast('refreshSlider');
    });

    // toggle close right sidebar
    $("a.sidetoggle").click(function(){
        var l = $(this).data('l');
        var width = $('#sidepanel').width();
        $("#sidepanel, .right-side-footer, .panel-head, .panel-title, .veil").animate({right: (l ?  0 : -width)}, 200);
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


    setInterval(function() {
        if ( (window.screenX != currentStreamboardLeft ) || (window.screenY != currentStreamboardTop)){
            currentStreamboardLeft = window.screenX;
            currentStreamboardTop = window.screenY;
            $.post('app/streamboard/set_streamboard_window_position', {left: currentStreamboardLeft, top: currentStreamboardTop});
            /*parent window can be closed*/
            if (window.opener) {
                window.opener.Pullr.Streamboard.streamboardLeft = currentStreamboardLeft;
                window.opener.Pullr.Streamboard.streamboardTop =  currentStreamboardTop;
            }
        }
    }, 1000)
    var $copySourceUrlButton = $("#btn-copy-source-link");
    var $tooltip = $('#copied-clipboard-tooltip');
    var client = new ZeroClipboard($copySourceUrlButton);
    client.on('aftercopy', function(){
        var top = $copySourceUrlButton.position().top + $copySourceUrlButton.height() + 10;
        var left = $copySourceUrlButton.position().left - ($tooltip.outerWidth() / 2 - $copySourceUrlButton.outerWidth() / 2);
        $tooltip.css({
            left: left,
            top: top
        });
        $tooltip.show();
    });
    $copySourceUrlButton.mouseout(function(){
        $tooltip.hide();
    })

});

$(window).load(function() {

    // streamboard loader
    $(".spinner-wrap").addClass('powered').fadeOut();

    // custom scrollbars
    $(".pane").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{
            preventDefault: true,
            scrollAmount: 20
        },
        scrollInertia: 30,
        callbacks:{
            whileScrolling: function(){
                $('.colorpicker').removeClass('colorpicker-visible');
            }
        },
        live: true
    });
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