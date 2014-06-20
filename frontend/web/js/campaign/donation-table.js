$(function(){
    $el = $('#campaign-donations');
//    $wrapper = $el.parent().parent();
    $el.dataTable({
         "bSort": false,
         "aLengthMenu": [
                    [10, 20, 40, -1],
                    [10, 20, 40, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": -1,
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
          }
    });
    
    
    // let's initiate popover
    var $popovers = $('[data-toggle="popover"');
    if ($popovers.length > 0){
        $popovers.popover();
    }
//    $wrapper.find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
//    $wrapper.find('.dataTables_length select').addClass("form-control"); // modify table per page dropdown
})
