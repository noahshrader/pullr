<?php
$lesscPath =  dirname(dirname(__DIR__)).'/node_modules/less/bin/lessc';
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
                    'less' => ['css', "$lesscPath {from} {to} --source-map --compress"],
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
