$(function () {
    // Custom scrollbars
    $("#sidebar, .campaigns-list-wrap, .site-content, .donations-list, #settingsTab .tab-content").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{preventDefault:true},
        scrollInertia: 100
    });
});