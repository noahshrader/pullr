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

// store sidebar memory
$(document).ready(function(){
    if (localStorage.getItem('menu-collapse') === "true"){
        $(".primary-nav-toggle").trigger("click");
    }
});

// toggle sidebar
$(".primary-nav-toggle").click(function(){
    $(".main-wrapper").toggleClass("large-menu-toggled small-menu-toggled");
    localStorage.setItem('menu-collapse', $(".main-wrapper").hasClass("small-menu-toggled"))
    $(".logo").toggleClass("icon-pullr icon-pullr2");
    $(".primary-nav-toggle .icon").toggleClass("icon-arrowleft2 icon-arrowright3");
});