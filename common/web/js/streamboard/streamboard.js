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

    $( window ).resize(function() {
        var width = $(window).width();
        var height = $(window).height();
        $.post('app/streamboard/set_streamboard_window', {width: width, height: height});
        window.opener.Pullr.Streamboard.streamboardWidth = width;
        window.opener.Pullr.Streamboard.streamboardHeight = height;
    });
});