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
$(function(){
    // store sidebar memory
    if (localStorage.getItem('menu-collapse') === "true"){
        $(".primary-nav-toggle").trigger("click");
    }
    // style selects
    $('select').select2({
        minimumResultsForSearch: -1
    });
    // color picker
    $('.colorpicker').colorpicker({
        format: 'hex',
        component: '.color-choice'
    });
    // custom scrollbars
    $(".pane").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{
            preventDefault: true,
            scrollAmount: 10
        },
        scrollInertia: 60,
        callbacks:{
            onTotalScrollBackOffset: 10,
            whileScrolling: function(){
                $('.colorpicker').removeClass('colorpicker-visible');
            },
            onScrollStart: function(){
                $('.top-menu').addClass('shadow');
            },
            onTotalScrollBack: function(){
                $('.top-menu').removeClass('shadow');
            }
        }
    });
});

// checkboxes
$('.checkbox label').click(function(event){
    event.preventDefault();
    $cbox = $(this).children('input:checkbox');
    if($cbox.attr('checked')) {
        $(this).removeClass('on');
        $cbox.removeAttr('checked');
    } else {
        $(this).addClass('on');
        $cbox.attr('checked', true);
    }
    $cbox.trigger('change');
});
$('.checkbox label input:checkbox').each(function(){
    if($(this).attr('checked')) {
        $(this).parent('label').addClass('on');
    }
});

// toggle sidebar
$(".primary-nav-toggle").click(function(){
    $(".main-wrapper").toggleClass("large-menu-toggled small-menu-toggled");
    localStorage.setItem('menu-collapse', $(".main-wrapper").hasClass("small-menu-toggled"))
    $(".logo").toggleClass("icon-pullr-logo icon-pullr-logo2");
    $(this).toggleClass("icon-arrow-left2 icon-arrow-right2");
});

// reduce sidebar if the window shrinks too much
$(window).resize(function() {  
    if($('.large-menu-toggled').width() < 900) {
        $('.main-wrapper').removeClass('large-menu-toggled').addClass('small-menu-toggled');
        $('.logo').removeClass('icon-pullr-logo').addClass('icon-pullr-logo2');
        $('.primary-nav-toggle .icon').removeClass('icon-arrowleft2').addClass('icon-arrowright3');
    }
});

$(window).load(function() {
    // loader
    $(".spinner-wrap").fadeOut();
});