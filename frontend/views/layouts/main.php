<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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

    <!-- Google Webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,400italic,700' rel='stylesheet' type='text/css'>

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
        $menuItems = [
        ];
        if (Yii::$app->user->isGuest) {
//            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
//            $loginUrl = (strpos(\Yii::$app->request->url, 'site/login')) > 0 ? \Yii::$app->request->url : '/site/login?return=' . urlencode(\Yii::$app->request->url);
//            $menuItems[] = ['label' => 'Login', 'url' => [$loginUrl]];
        } else {
            $logoutUrl = 'app/site/logout?return=' . urlencode(\Yii::$app->request->url);
            $logoutLink = '<li><a href="' . $logoutUrl . '" > Logout (';
            $logoutLink .= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'logoPhoto']]);
            $logoutLink .= Yii::$app->user->identity->name . ')</a></li>';
            $menuItems[] = $logoutLink;
        }
//
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

    <!-- BEGIN Main Sidebar -->
    <?php $this->beginBody() ?>
        <div class="page-wrapper">
            <header class="header-sidebar col-xs-2">
                <? if (!Yii::$app->user->isGuest): ?>
                   <?= $this->render('@common/views/leftmenu/avatar'); ?>
                <? endif; ?>
                <ul class="nav nav-stacked"> 
                    <? if (Yii::$app->user->isGuest): ?>
                    <li>
                         <?= $this->render('@frontend/views/site/signupModal'); ?>  
                    </li>
                    <li>
                         <?= $this->render('@frontend/views/site/loginModal'); ?>  
                    </li>
                    <? endif; ?>
                    <? if (!Yii::$app->user->isGuest): ?>
                        <li>
                            <a class="icon-statistics" href="app">Dashboard</a>
                        </li>
                        <li>
                            <a class="icon-text" href="app/campaigns">Campaign</a>
                        </li>
                        <li>
                            <a class="icon-heart2" href="app/donation">Donations</a>
                        </li>
                        <li>
                            <a class="icon-popup" href="app/alerts">Alerts</a>
                        </li>
                        <li>
                            <a class="icon-cog" href="app/settings">Settings</a>
                        </li>
                           
                    <? endif; ?>
                </ul>
            </header>
            <div class="page-container">
            <?= Alert::widget() ?>
            <?= $content ?>
            </div>


<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
