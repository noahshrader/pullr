<?php
return [
    'firstGiving' => [
        'donateHost' => 'https://donate.firstgiving.com',
        'formStyleSheetURL' => '/layoutview/payment.css',
        'donationApiHost' => 'https://api.firstgiving.com/',
        //dont change keys of array
        'callbackSuccessPair' => [
            'key' => 'pjrvodxtkh',
            'value' => 'zyxkViEajImQdIM'
        ],
        'affiliate' =>[
            'common' => 'Pullr',
            'pro' => 'Pull-pro'
        ]
    ],
    'payPalHost' => 'https://www.paypal.com',
    'payPalDonationFeeReceiver' => 'pullforgood@gmail.com',
    'payPal' => [
        'log.LogEnabled' => false,
        'mode' => 'live',
        'acct1.UserName' => '',
        'acct1.Password' => '',
        'acct1.Signature' => '',
        'acct1.AppId' => ''
    ]
];
