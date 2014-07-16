var Pullr = Pullr || {};
function log(text) {
    if (window.console) {
        window.console.log(text);
    }
}

/*submit form on CTRL+ENTER*/
function catchKeys() {
    $(document).on('keydown', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            log(8);
            var $target = $(event.target);
            /**
             * first let's try to sumbit parents form if it exist.
             */
            var $form = $target.parents('form');
            if ($form.length === 0){
                $form = $('form');
            }
            if ($form.length === 1){
                $form.submit();
            }
        }
    });

}

(catchKeys());