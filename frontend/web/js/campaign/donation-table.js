$(function(){
    $el = $('#campaign-donations');
    $wrapper = $el.parent().parent();
    var $table = $el.dataTable({
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
    
    
    // Add event listener for opening and closing details
    $('#campaign-donations tbody').on('click', 'td.details-control', function () {
        var dt = $('#campaign-donations').DataTable();
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
    
    if ($table.fnSettings().fnRecordsTotal()){
        var id = $('.campaign-view-selected').data('id');
        var href = 'app/campaign/exportdonations?id='+id;

        var csvButton = $('<a>').addClass('btn btn-info btn-csv btn-sm').attr('href', href).text('CSV');
        csvButton.insertAfter($wrapper.find('.dataTables_info'));
    }
//    $wrapper.find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
//    $wrapper.find('.dataTables_length select').addClass("form-control"); // modify table per page dropdown
})


function formatChildRow ( $row ) {
    return '<table class="childTable" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td><em>Comments:</em> '+$row.data('comments')+'</td>'+
            '<td><em>Email Address:</em> '+$row.data('email')+'</td>'+
        '</tr>'+
    '</table>';
}