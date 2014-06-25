function donorsWithFilter(donors){
    var viewModel = {
        query: ko.observable('')
    };

    viewModel.donors = ko.dependentObservable(function() {
        var search = this.query().toLowerCase();
        return ko.utils.arrayFilter(donors, function(donor) {
            return ( (donor.name.toLowerCase().indexOf(search) >= 0) 
                    || (donor.nameFromForm.toLowerCase().indexOf(search) >= 0) 
                    || (donor.email.toLowerCase().indexOf(search) >= 0)
                    );
        });
    }, viewModel);

    ko.applyBindings(viewModel);
}