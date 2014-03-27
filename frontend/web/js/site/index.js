function showAuthModal(){
    if ($('#confirmationEmailModal').length > 0 ){
        $('#confirmationEmailModal').modal('show');
        return;
    }
    var $el = $('#signupModal');
    if ($el.length > 0){
        $el.modal('show');
    }
}

$(function() {
    showAuthModal();
});
