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
        if(! $(this).data('comments').length) return;
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

    if ($table.fnSettings().fnRecordsTotal()){
        if ($('.donor-view-wrap').length > 0){
            
            var email = $('#content').data('email');
            var href = 'app/campaigns/exportdonations?email='+encodeURI(email);
        } else {
            var id = $('#content').data('id');
            var href = 'app/campaigns/exportdonations?id='+id;
        }

        /* CSV Button */
        var csvButton = $('<a>').addClass('btn btn-secondary btn-sm').attr('href', href).html
            ('<i class="icon icon-download2"></i> Export All');
        csvButton.prependTo($wrapper.find('.table-footer'));

        /* Manual Donations */
        var addDonationButton = $('<a>').addClass('btn btn-primary btn-sm').html
        ('<i class="icon icon-plus2"></i> Add donation')
            .addClass("manual-donation")
            .attr('data-target', '#manualDonationModal')
            .attr('data-toggle', 'modal');
        addDonationButton.prependTo($wrapper.find('.campaign-table .table-footer'));
    }

    // Rotate table details area on click
    $('tr.donation-entry').click(function() {
        if ($(this).data('comments').length) {
            $(this).toggleClass('drop');
        }
    });
    
    $wrapper.find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
    $wrapper.find('.dataTables_length select').addClass("form-control"); // modify table per page dropdown
})


function formatChildRow ( $row ) {
    return '<table class="childTable" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr class="open-row">'+
            '<td>'+$row.data('comments')+'</td>'+
        '</tr>'+
    '</table>';
}