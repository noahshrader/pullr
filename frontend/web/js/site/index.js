// Sidebar scripts
$('a.openone').click(function() {
    $('#sidepanel, .container, .primary-header, nav.sub-nav').toggleClass('open');
    $('body, html').toggleClass('freeze');
    $('#sidepaneltwo').toggleClass('hide').toggleClass('open');
    return false;
});
$('a.closetwo, .sidepanel ul.nav-tabs li a').click(function() {
    $('#sidepanel, #sidepaneltwo, .container, .primary-header, nav.sub-nav').removeClass('expand');
});
$('a.opentwo').click(function() {
    $('#sidepanel, #sidepaneltwo, .container, .primary-header, nav.sub-nav').addClass('open expand');
    $('body').addClass('freeze');
    return false;
});
