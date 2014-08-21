function dashboardCloseSystemMessage(id){
    $.get('app/dashboard/closesystemmessage', {id: id}, function(){
        $('.systemNotification').remove();
    })
}