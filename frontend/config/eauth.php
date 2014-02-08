<?php

return [
    'class' => 'nodge\eauth\EAuth',
    'popup' => true, // Use the popup window instead of redirecting.
    'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
    'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
    'httpClient' => [
// uncomment this to use streams in safe_mode
//'useStreamsFallback' => true,
    ],
    'services' => [// You can change the providers and their classes.
//        'google' => [
//            'class' => 'nodge\eauth\services\GoogleOpenIDService',
////'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
//        ],        
        'google_oauth' => [
            // register your app here: https://code.google.com/apis/console/
            'class' => 'common\components\oauth\IBCGoogleService',
            'clientId' => '959544113849-4b16pbc81js1c1p312bbvemfpuu5pkk5.apps.googleusercontent.com',
            'clientSecret' => 'dIXnsLBsXkx0mWT_tSw889G8',
            'scope' => ['USERINFO_PROFILE', 'USERINFO_EMAIL'],
        ],
        'facebook' => array(
            // register your app here: https://developers.facebook.com/apps/
            'class' => 'common\components\oauth\IBCFacebookService',
            'clientId' => '720235521330099',
            'clientSecret' => '023d306a1682daec9f6c73d7eada6d0d',
        ),
        'twitter' => array(
            // register your app here: https://dev.twitter.com/apps/new
            'class' => 'common\components\oauth\IBCTwitterService',
            'key' => '',
            'secret' => '',
        ),
//        'yandex' => [
//            'class' => 'nodge\eauth\services\YandexOpenIDService',
//        //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
//        ],
//        'yandex_oauth' => array(
//            // register your app here: https://oauth.yandex.ru/client/my
//            'class' => 'nodge\eauth\services\YandexOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//            'title' => 'Yandex (OAuth)',
//        ),
//        'yahoo' => array(
//            'class' => 'nodge\eauth\services\YahooOpenIDService',
//        //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
//        ),
//        'linkedin' => array(
//            // register your app here: https://www.linkedin.com/secure/developer
//            'class' => 'nodge\eauth\services\LinkedinOAuth1Service',
//            'key' => '...',
//            'secret' => '...',
//            'title' => 'LinkedIn (OAuth1)',
//        ),
//        'linkedin_oauth2' => array(
//            // register your app here: https://www.linkedin.com/secure/developer
//            'class' => 'nodge\eauth\services\LinkedinOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//            'title' => 'LinkedIn (OAuth2)',
//        ),
//        'github' => array(
//            // register your app here: https://github.com/settings/applications
//            'class' => 'nodge\eauth\services\GitHubOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//        ),
//        'live' => array(
//            // register your app here: https://account.live.com/developers/applications/index
//            'class' => 'nodge\eauth\services\LiveOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//        ),
//        'steam' => array(
//            'class' => 'nodge\eauth\services\SteamOpenIDService',
//        //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
//        ),
//        'vkontakte' => array(
//            // register your app here: https://vk.com/editapp?act=create&site=1
//            'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//        ),
//        'mailru' => array(
//            // register your app here: http://api.mail.ru/sites/my/add
//            'class' => 'nodge\eauth\services\MailruOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//        ),
//        'odnoklassniki' => array(
//            // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
//            // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
//            'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
//            'clientId' => '...',
//            'clientSecret' => '...',
//            'clientPublic' => '...',
//            'title' => 'Odnoklas.',
//        ),
    ]
];
