<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use common\assets\CommonAsset;
use common\components\Application;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

CommonAsset::register($this);
\common\assets\BackendAsset::register($this);


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
        <div class="main-wrapper">
        
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

            <?= $content ?>

<?php $this->endBody() ?>
    </div>
    </body>
</html>
<?php $this->endPage() ?>