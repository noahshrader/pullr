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

// Rotate table details area on click
$('tr.donation-entry').click(function() {
    $(this).toggleClass('drop');
});

// Show/hide campaign actions menu
$('ul.campaign-quick-links > li > a').click(function(e) {
    e.stopPropagation();
    $('ul.campaign-quick-links').toggleClass('drop');
});
$('body').click(function(){ 
    $('ul.campaign-quick-links').removeClass('drop');
});

$(window).load(function() {
    // streamboard loader
    $(".spinner-wrap").fadeOut();
});