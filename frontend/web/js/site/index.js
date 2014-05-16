function showAuthModal(){
    if ($('#confirmationEmailModal').length > 0 ){
        $('#confirmationEmailModal').modal('show');
        return;
    }
    var $el = $('#loginModal');
    if ($el.length > 0){
        $el.modal('show');
    }
}

$(function() {
    showAuthModal();
});
