$(function () {
    // Custom scrollbars
    $(".campaigns-list-wrap, .site-content, .donations-list").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{preventDefault:true},
        mouseWheel:{scrollAmount:20},
        scrollInertia: 0
    });
});