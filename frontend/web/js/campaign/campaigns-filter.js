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

// more top padding to campaigns view?
if ($('.campaigns-list').find('.list-search').length) {
    $('.campaigns-list').addClass('padtop');
}