function dashboardCloseSystemMessage(id){
    $.get('app/dashboard/closesystemmessage', {id: id}, function(){
        $('.systemNotification').remove();
    })
}

(function twitchEventsMonitor() {
    if (window.Twitch) {
        var channelName = Pullr.user.userFields.twitchChannel;
        var clientId = Pullr.twitchClientId;

        if (channelName) {
            $.ajax({
                type:'post',
                url:'app/streamboard/get_followers',
                success: function() {
                    console.log('Update followers successful');

                    if (Pullr.user.userFields.twitchPartner) {
                        $.ajax({
                            type:'post',
                            url:'app/streamboard/get_subscribers',
                            success: function(){
                                console.log('Update subscribers successful');
                            }
                        });
                    }

                }
            });

        }
    }
})();