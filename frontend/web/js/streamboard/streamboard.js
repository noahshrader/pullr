$(function () {
    $(".resizable").resizable({
//        maxHeight: 150,
        maxWidth: (screen.width/2),
//        minHeight: 150,
        minWidth: 287,
        handles: "w",
        animate: false,
        delay: 0,
        resize: function( event, ui ) {
            $(".resizable").css('left', 'auto');
        }
    });

//    $( ".resizable" ).resizable( "option", "maxWidth", '50%' );

});