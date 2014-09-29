<?php
use common\components\Application;
$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'bootstrap' => ['log'],
    'id' => Application::ID_FRONTEND,
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'app/fgcallback' => 'site/fgcallback',
                'app/privacy' => 'site/privacy',
                'app/termsofservice' => 'site/termsofservice',
                'app/<controller>/<action>' => '<controller>/<action>',
                'app/<controller>' => '<controller>',
                'app' => 'site',
                'api/<action>' => 'api/<action>',
                '<userAlias>/<campaignAlias>' => 'layoutview/view',
                '<userAlias>/<campaignAlias>/donate' => 'layoutview/donate',
                '<userAlias>/<campaignAlias>/json' => 'layoutview/json',
                '<userAlias>/<campaignAlias>/thankyou' => 'layoutview/thankyou'
            ]
        ],
        'request'=>array(
            'enableCsrfValidation'=>false,
        ),
        'user' => [
             'class' => 'common\components\PullrUserComponent',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '@web'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'some_pullr_key'
        ],
    ],
    'params' => $params,
];
