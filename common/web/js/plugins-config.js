$(function () {
    // Custom scrollbars
    $(".campaigns-list-wrap, .site-content").mCustomScrollbar({
        theme:"minimal",
        setTop: "60px",
        mouseWheel:{preventDefault:true},
        scrollInertia: 0
    });
});