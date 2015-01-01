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
    $(".main-wrapper").mCustomScrollbar({
        theme:"minimal",
        mouseWheel:{
            preventDefault: true,
            scrollAmount: 20
        },
        scrollInertia: 30,
        callbacks:{
            onTotalScrollBackOffset: 10,
            whileScrolling: function(){
                $('.colorpicker').removeClass('colorpicker-visible');
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

// enable tooltips
$("[data-toggle='tooltip']").tooltip({
    html: true,
    delay: {
        show: 400,
        hide: 100
    }
});

$(window).load(function() {
    // loader
    $(".spinner-wrap").fadeOut();
});