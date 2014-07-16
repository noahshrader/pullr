<?php
/**
 * that file is included by frontend/web/index.php and backend/web/index.php
 */
function PullrMain($DIR){
        require($DIR . '/../../vendor/autoload.php');
        require($DIR . '/../../vendor/yiisoft/yii2/Yii.php');
        require($DIR . '/../../common/config/aliases.php');

        $config = yii\helpers\ArrayHelper::merge(
            require($DIR . '/../../common/config/main.php'),
            require($DIR . '/../../common/config/main-local.php'),
            require($DIR . '/../config/main.php'),
            require($DIR . '/../config/main-local.php')
        );

        $application = new common\components\Application($config);
        $application->run();
    }
?>