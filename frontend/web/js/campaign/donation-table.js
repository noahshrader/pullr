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
    $el.find('tbody').on('click', 'td.details-control i', function () {
        var dt = $el.DataTable();
        $row = $(this).closest('tr');
        var row = dt.row( $row );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            $row.find('td:first-child i').toggleClass('glyphicon-plus-sign glyphicon-minus-sign')
        }
        else {
            // Open this row
            row.child( formatChildRow($row) ).show();
            $row.find('td:first-child i').toggleClass('glyphicon-plus-sign glyphicon-minus-sign')
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
        var csvButton = $('<a>').addClass('btn btn-info btn-csv btn-sm').attr('href', href).html
            ('<i class="glyphicon glyphicon-download-alt"></i> CSV');
        csvButton.insertBefore($wrapper.find('.dataTables_paginate'));
    }
    
    $wrapper.find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
    $wrapper.find('.dataTables_length select').addClass("form-control"); // modify table per page dropdown
})


function formatChildRow ( $row ) {
    return '<table class="childTable" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td><em>Comments:</em> '+$row.data('comments')+'</td>'+
            '<td class="email"><em>Email Address:</em> '+$row.data('email')+'</td>'+
        '</tr>'+
    '</table>';
}