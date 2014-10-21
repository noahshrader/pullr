<?php
return [
    /**
     * Use that link to add/change your application
     * @url http://www.twitch.tv/settings/connections
     */
    'twitchClientId' => 'l7mj3pfjvxpk2zv6ivr9jpisodqd5h0',
    'twitchClientSecret' => 'borb565z4l7kzhawe3sb4wufrjfgw4b',

    'payPalHost' => 'https://www.sandbox.paypal.com',
    'payPalDonationFeeReceiver' => 'pullforgood-facilitator@gmail.com',
    'payPal' => [
        'log.LogEnabled' => false,
        'mode' => 'sandbox',
        'acct1.UserName' => 'donation.klyukin_api1.gmail.com',
        'acct1.Password' => '1404753458',
        'acct1.Signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31ANSA70LvZSZGxQfP0Cnh0IcGTUCJ',
        'acct1.AppId' => 'APP-80W284485P519543T'
    ],

    'firstGiving' => [
        'donateHost' => 'https://donatenowstaging.firstgiving.com',
        'formStyleSheetURL' => 'https://dl.dropbox.com/s/s7d43ysw7nppro1/donation.css',
        'donationApiHost' => 'http://usapisandbox.fgdev.net/',
        'callbackSuccessPair' => [
            'key' => 'kobdffjuux',
            'value' => 'ieDEaolClvseqLF'
        ],
        'affiliate' =>[
            'common' => 'Pullr',
            'pro' => 'Pull-pro'
        ]
    ]
];
