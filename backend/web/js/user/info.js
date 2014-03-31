$(function() {
    $el = $('#user-events');
    $wrapper = $el.parent().parent();
    $el.dataTable({
        bPaginate : false, 
        bFilter : false, 
        bInfo: false
    });
})
