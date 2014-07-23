var Pullr = Pullr || {};
function log(text) {
    if (window.console) {
        window.console.log(text);
    }
}

/*submit form on CTRL+ENTER*/
function catchKeys() {
    $(document).on('keydown', function (event) {
        if (event.ctrlKey && event.keyCode === 13) {
            log(8);
            var $target = $(event.target);
            /**
             * first let's try to sumbit parents form if it exist.
             */
            var $form = $target.parents('form');
            if ($form.length === 0) {
                $form = $('form');
            }
            if ($form.length === 1) {
                $form.submit();
            }
        }
    });

}

/**
 *
 * format number with commas. Example - 1,200,322
 */
function number_format(number) {
    var str = '';
    var module = number % 1000;
    str = module + str;
    remainNumber = Math.floor(number / 1000);
    if (remainNumber > 0) {
        if (module < 100) {
            str = '0' + str;
        }
        if (module < 10) {
            str = '0' + str;
        }
        str = number_format(remainNumber) + ',' + str;
    }
    return str;
}

function twitchEventsMonitor() {
    if (Pullr.user.uniqueName) {
        Twitch.init({clientId: 'l7mj3pfjvxpk2zv6ivr9jpisodqd5h0'}, function (error, status) {
            var method = 'channels/' + Pullr.user.uniqueName + '/follows';
            log(method);
            Twitch.api({method: method, params: {} }, function (error, list) {
                console.debug(list);
            });
        });
    }
}

(catchKeys());
