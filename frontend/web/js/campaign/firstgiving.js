$(document).ready(function() {

    function formatResultCharity(item) {
        //example
        return '<span style="color: red">' +  item.organization_name + '</span><br><span style="color:green; font-size: 10px;">' + item.organization_name + '</span>';
    }

    function formatSelectionCharity(item) {
        return item.organization_name;
    }

    $("#firstgiving").select2({
        placeholder: "Search for a charity",
        minimumInputLength: 3,
        id: function (item) {
            return item.organization_uuid;
        },
        ajax: {
            dataType: 'jsonp',
            contentType: 'application/json',
            data: function (term) {
                return {
                    q: 'organization_name:*' + term.replace(/'|"/g, '') + '*'
                }
            },
            quietMillis: 1000,
            url: 'http://graphapi.firstgiving.com/v1/list/organization?jsonpfunc=?',
            results: function(data) {
                return { results: data.payload };
            }
        },
        initSelection: function (element, callback) {
            var uuid = $(element).val();
            if (uuid !== "") {
                $.ajax({
                    dataType: 'jsonp',
                    contentType: 'application/json',
                    jsonp: 'jsonpfunc',
                    url: 'http://graphapi.firstgiving.com/v1/object/organization/' + uuid + '?jsonpfunc=?'
                }).done(function(data) { callback(data.payload); });
            }
        },
        formatResult: formatResultCharity,
        formatSelection: formatSelectionCharity,
        containerCssClass: 'form-control',
        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
    });
});