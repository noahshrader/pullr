var Pullr = Pullr || {};
function log(text) {
    if (window.console) {
        window.console.log(text);
    }
}

// submit form on CTRL+ENTER
function catchKeys() {
    $(document).on('keydown', function (event) {
        if (event.ctrlKey && event.keyCode === 13) {
            var $target = $(event.target);
            // first let's try to sumbit parents form if it exist.
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

//format number with commas. Example - 1,200,322
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

/**
 * 
 * get current user timezone
 */
function timezone()
{
    var today = new Date();
    var jan = new Date(today.getFullYear(), 0, 1);
    var jul = new Date(today.getFullYear(), 6, 1);
    var dst = today.getTimezoneOffset() < Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());

    return {
        offset: -(today.getTimezoneOffset()/60),
        dst: +dst
    };
}

function twitchEventsMonitor() {
//     if (window.Twitch) {
//         var channelName = Pullr.user.userFields.twitchChannel;
//         var clientId = Pullr.twitchClientId;
//         log(clientId);
//         log(channelName);
//         if (channelName) {
//             Twitch.init({clientId: clientId}, function (error, status) {
//                 var method = 'channels/' + channelName + '/follows';
//                 Twitch.api({method: method, params: {limit: 100} }, function (error, list) {
//                     $.post('app/twitch/update_follows_ajax', {data: JSON.stringify(list)});
//                 });

//                 if (Pullr.user.userFields.twitchPartner){
//                     $.post('app/twitch/update_subscriptions_ajax', {});
// //                    method = 'channels/' + channelName + '/subscriptions'
// //                    Twitch.api({method: method, params: {limit: 100} }, function (error, list) {
// //                        $.post('app/twitch/update_subscriptions_ajax', {data: JSON.stringify(list)});
// //                    });
//                 }
//             });
//         }
//     }
}

(catchKeys());