<?php

$lessc_path = 'lessc';
if(empty($_SERVER["WINDIR"]) || $_SERVER["WINDIR"]!='C:\Windows') {
    $lessc_path = '/usr/local/bin/lessc';
}

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'class' => \yii\web\AssetManager::className(),
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ]
            ],
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', '/usr/local/bin/lessc {from} {to} --source-map --compress'],
                ],
            ],
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD', //GD or Imagick
        ],
        'authManager' => [
            'class' => \common\components\PhpManager::className(),
            'defaultRoles' => ['guest'],
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
    ],
];
