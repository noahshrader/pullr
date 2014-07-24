$(function () {
    // Custom scrollbars
    $("#sidebar, .campaigns-list-wrap, .site-content, .donations-list").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{preventDefault:true},
        scrollInertia: 200
    });
});