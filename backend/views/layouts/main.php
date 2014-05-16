<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\assets\CommonAsset;
use common\widgets\user\UserPhoto;
use common\components\Application;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

CommonAsset::register($this);
\backend\assets\BackendAsset::register($this);


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
        <?php
        NavBar::begin([
            'brandLabel' => 'pullr',
            'brandUrl' => ".",
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top top-menu',
            ],
        ]);
        
        NavBar::end();
        ?>
<?php $this->beginBody() ?>
        <div class="page-wrapper">
            <div class="page-sidebar">
                <? if (Application::IsAdmin()): ?>
                    <? if (!Yii::$app->user->isGuest): ?>
                         <?= $this->render('@common/views/leftmenu/avatar'); ?>
                    <? endif; ?>
                    <ul> 

                            <li>
                                <a href=".">Reports</a>
                            </li>
                            <li>
                                <a href="user">Users</a>
                            </li>
                            <li>
                                <a href="campaign">Campaigns</a>
                            </li>
                            <li>
                                <a href="charity">Charities</a>
                            </li>
                            <li>
                                <a href="theme">Themes</a>
                            </li>
                    </ul>
                <? endif; ?>
            </div>
            <div class="page-container">
                <div class="container">
                    <?= $content ?>
                </div>
            </div>


<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>