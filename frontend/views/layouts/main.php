<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\FrontendAsset;
use frontend\widgets\Alert;
use common\widgets\user\UserPhoto;
use common\assets\CommonAsset;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);
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
            'brandUrl' => "/",
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top top-menu',
            ],
        ]);
        $menuItems = [
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
            $loginUrl = (strpos(\Yii::$app->request->url, 'site/login')) > 0 ? \Yii::$app->request->url : '/site/login?return=' . urlencode(\Yii::$app->request->url);
            $menuItems[] = ['label' => 'Login', 'url' => [$loginUrl]];
        } else {
            $logoutUrl = 'site/logout?return=' . urlencode(\Yii::$app->request->url);
            $logoutLink = '<li><a href="' . $logoutUrl . '" > Logout (';
            $logoutLink .= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'logoPhoto']]);
            $logoutLink .= Yii::$app->user->identity->name . ')</a></li>';
            $menuItems[] = $logoutLink;
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
<?php $this->beginBody() ?>
        <div class="page-wrapper">
            <div class="page-sidebar">
                <? if (!Yii::$app->user->isGuest): ?>
                    <?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?>
                <? endif; ?>
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
                            <a href="">Dashboard</a>
                        </li>
                        <li>
                            <a href="pullrlayout">Streams</a>
                        </li>
                        <li>
                            <a href="site/settings">Settings</a>
                        </li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="page-container">
                    <?=
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
<?= Alert::widget() ?>
            <?= $content ?>
            </div>


<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
