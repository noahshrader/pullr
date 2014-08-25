<?php

return [
    'adminEmails' => ['s.klyukin@yandex.ru', 'noahshrader@gmail.com', 'pullr@yandex.com'],
    'mailFrom' => 'noreply@flaper.info',
    'baseUrl' => 'http://pullr.io',
    'user.passwordResetTokenExpire' => 3600,
    'frontendUrl' => '../../frontend/web/',
    'backendUrl' => '../../backend/web/',
    'monthSubscription' => 3.99,
    'yearSubscription' => 48,
    /*overright it in params-local.php as params-local is not included in git repository*/
    'paypalClientId' => 'AdhS6hAM2klW0zvrByqMTUAwosKCt8kMrhUPN6-HHzoCaJscFJHsGfGUvLzP',
    'paypalClientSecret' => 'EHYMZhAE29WHfY8T37s-j-2wMOL8SMjjB3uX-9h9uz2snme0pL_tKYLg3YK4',
    /*use that link to add/change your application
    * "http://www.twitch.tv/settings/connections"
     */
    'twitchClientId' => 'remsfof2g40lldoyt81lkldftlis4e7',
    'twitchClientSecret' => '2xjixm2240k3gj7jwd90hupd9of4kwm',

    'googleAPIKey' => 'AIzaSyBCaACEmXOZ9F2u9DF9O-U-1-_BmTfNQfE',
    //First Giving params
    'firstGiving' => [
        'donateHost' => 'https://donatenowstaging.firstgiving.com',
        'formStyleSheetURL' => 'https://www.dropbox.com/s/s7d43ysw7nppro1/donation.css',
        'donationApiHost' => 'http://usapisandbox.fgdev.net/',
        //dont change keys of array
        'callbackSuccessPair' => [
            'key' => 'kobdffjuux',
            'value' => 'ieDEaolClvseqLF'
        ]
    ]
];
