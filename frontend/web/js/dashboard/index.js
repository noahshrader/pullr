function dashboardCloseSystemMessage(id){
    $.get('app/dashboard/closesystemmessage', {id: id}, function(){
        $('.systemNotification').remove();
    })
}

// dashboard alert
$(window).load(function() {
    $('.alert-info').addClass('slideup');
});