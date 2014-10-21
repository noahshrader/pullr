<?php
return [
    'payPalHost' => 'https://www.paypal.com',
    'payPalDonationFeeReceiver' => 'pullforgood@gmail.com',
    'payPal' => [
        'log.LogEnabled' => false,
        'mode' => 'live',
        'acct1.UserName' => '',
        'acct1.Password' => '',
        'acct1.Signature' => '',
        'acct1.AppId' => ''
    ],

    'firstGiving' => [
        'donateHost' => 'https://donate.firstgiving.com',
        'formStyleSheetURL' => '/layoutview/payment.css',
        'donationApiHost' => 'https://api.firstgiving.com/',
        'callbackSuccessPair' => [
            'key' => 'pjrvodxtkh',
            'value' => 'zyxkViEajImQdIM'
        ],
        'affiliate' =>[
            'common' => '',
            'pro' => ''
        ]
    ]
];
