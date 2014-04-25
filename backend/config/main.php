<?php
use common\components\Application;
$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => Application::ID_BACKEND,
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'modules' => [],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ]
            ],
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --source-map --compress'],
                ],
            ],
        ],
        'user' => [
            'class' => 'common\components\PullrUserComponent',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => 'admin/login',
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
            'errorAction' => 'admin/error',
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'report'
];
