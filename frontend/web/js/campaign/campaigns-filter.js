function campaignsWithFilter(campaigns){
    var viewModel = {
        query: ko.observable('')
    };

    viewModel.campaigns = ko.dependentObservable(function() {
        var search = this.query().toLowerCase();
        return ko.utils.arrayFilter(campaigns, function(campaign) {
            return campaign.name.toLowerCase().indexOf(search) >= 0;
        });
    }, viewModel);

    ko.applyBindings(viewModel);
}

// reduce top padding to campaigns view?
if ($('.campaigns-list').find('.list-search').length == 0) {
    $('.campaigns-list').addClass('redpad');
}
// Rotate table details area on click
$('tr.donation-entry').click(function() {
    $(this).children('td.details-control').toggleClass('drop');
});