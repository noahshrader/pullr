<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use common\widgets\user\UserPhoto;
use common\assets\CommonAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
        <?php $this->head() ?>
    </head>
    <body>

<?php $this->beginBody() ?>
        <div class="page-wrapper">
            <div class="page-sidebar">
                <ul> 
                    <? if (Yii::$app->user->isGuest): ?>
                    <li>
                        <a href="site/signup">Signup</a>
                    </li>
                    <li>
                        <a href="site/login">Login</a>
                    </li>
                    <? endif; ?>
                    <? if (!Yii::$app->user->isGuest): ?>
                        <li>
                            <a href="site/settings">Settings</a>
                        </li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="page-container">
                <div class="container">
                    <?=
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
<?= Alert::widget() ?>
            <?= $content ?>
                </div>
            </div>


<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
