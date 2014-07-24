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

    $('ul.bottom-panel-nav li a').click(function() {
   		$('.'+$(this).data('panel')+'_panel').toggleClass('selected');
   	});
    $('a.close').click(function() {
   		$(this).closest('div').toggleClass('selected');
   	});

    // make source iframe adjsut to height of inner content
    $('#sourcecode').iframeHeight();

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