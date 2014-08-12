$(function(){
    $el = $('#donations-table');
    $wrapper = $el.parent().parent();
    var $table = $el.dataTable({
         "bSort": false,
         "aLengthMenu": [
                    [10, 20, 40, -1],
                    [10, 20, 40, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 10,
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
          }
    });

    // Add event listener for opening and closing details
    $el.find('tbody').on('click', 'tr.odd, tr.even', function () {
        var dt = $el.DataTable();
        $row = $(this).closest('tr');
        var row = dt.row( $row );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
        }
        else {
            // Open this row
            row.child( formatChildRow($row) ).show();
        }
    } );
    /*add csv button*/
    if ($table.fnSettings().fnRecordsTotal()){
        if ($('.donor-view').length > 0){
            
            var email = $('.donor-view').data('email');
            var href = 'app/campaign/exportdonations?email='+encodeURI(email);
        } else {
            var id = $('.campaign-view-selected').data('id');
            var href = 'app/campaign/exportdonations?id='+id;
        }
        var csvButton = $('<a>').addClass('btn btn-csv btn-secondary btn-sm').attr('href', href).html
            ('<i class="icon icon-download"></i> Export All');
        csvButton.insertAfter($wrapper.find('.dataTables_paginate'));
    }
    
    $wrapper.find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
    $wrapper.find('.dataTables_length select').addClass("form-control"); // modify table per page dropdown
})


function formatChildRow ( $row ) {
    return '<table class="childTable" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr class="open-row">'+
            '<td><em>Comments:</em> '+$row.data('comments')+'</td>'+
        '</tr>'+
    '</table>';
}