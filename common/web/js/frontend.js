/*add class active to one item of main menu
 */
Pullr.setCurrentMenuActive = function(){
    var regexp = new RegExp(Pullr.baseUrl+'/?([^/\?]*)', 'i');
    var matchResult = location.href.match(regexp);
    var match = ''
    if (matchResult !== null){
        match = matchResult[1];
    } else{
        /*if link without app - that is only main page*/
        match = '';
    }
    if (match != ""){
        match = '/'+match;
    }
    match = 'app'+match;
    var selector = '.sidebar-nav.nav-top a[href="'+match+'"]';
    $(selector).addClass('active');
};

// open streamboard in separate window
$('.streamboard').click(function(event) {
    event.preventDefault();
    var width = Math.min(screen.width,Pullr.Streamboard.streamboardWidth);
    var height = Math.min(screen.height, Pullr.Streamboard.streamboardHeight);
    var left = Math.min(screen.width-width, Pullr.Streamboard.streamboardLeft);
    var top = Math.min(screen.height-height, Pullr.Streamboard.streamboardTop);
    window.open($(this).attr("href"),"popupWindow","width="+width+",height="+height+
        ",left="+left+",top="+top+",scrollbars=yes");
});

// enable tooltips
$("[data-toggle='tooltip']").tooltip();

$('select').selectpicker();

// dashboard alert
$(window).load(function() {
      $('.alert-info').addClass('slideup');
});

// dashboard notifications / campaign invites
$(function () {
    var invites = $('.campaign-invites ul li').length;
    var invitelist = $('.campaign-invites ul');
    $('.invites-count').html(invites);
    if ($(invitelist).find('li').length >= 1) {
        $('.icon-announcement').addClass('full');
    }
    $('.icon-announcement').click(function () {
        $(invitelist).toggleClass('hide');
    });
});

// toggle sidebar
$(".primary-nav-toggle").click(function(){
    $(".main-wrapper").toggleClass("large-menu-toggled small-menu-toggled");
    $(".logo").toggleClass("icon-pullr icon-pullr2");
    $(".primary-nav-toggle .icon").toggleClass("icon-arrowleft2 icon-arrowright3");
});

// add class to body of current url segment
var value = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
$('body').addClass(value);