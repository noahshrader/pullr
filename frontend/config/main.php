<?php
use common\components\Application;
$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => Application::ID_FRONTEND,
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login/<service:google_oauth|twitter|facebook|>' => 'site/login',
                'app/<controller>/<action>' => '<controller>/<action>',
                'app/<controller>' => '<controller>',
                'app' => 'site',
                'api/<action>' => 'api/<action>',
                '<userAlias>/<campaignAlias>' => 'layoutview/view',
                '<userAlias>/<campaignAlias>/donate' => 'layoutview/donate',
                '<userAlias>/<campaignAlias>/thankyou' => 'layoutview/thankyou'
            ]
        ],
        'request'=>array(
            'enableCsrfValidation'=>false,
        ),
        'eauth' => require 'eauth.php',
        'user' => [
             'class' => 'common\components\PullrUserComponent',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
    ],
    'params' => $params,
];
