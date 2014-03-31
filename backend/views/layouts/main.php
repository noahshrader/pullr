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
        $menuItems = [
        ];
        if (Yii::$app->user->isGuest) {
            $loginUrl = (strpos(\Yii::$app->request->url, 'admin/login')) > 0 ? \Yii::$app->request->url : '/admin/login?return=' . urlencode(\Yii::$app->request->url);
            $menuItems[] = ['label' => 'Login', 'url' => [$loginUrl]];
        } else {
            $logoutUrl = 'admin/logout?return=' . urlencode(\Yii::$app->request->url);
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
                <? if (Application::IsAdmin()): ?>
                    <? if (!Yii::$app->user->isGuest): ?>
                        <?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?>
                    <? endif; ?>
                    <ul> 

                            <li>
                                <a href=".">Reports</a>
                            </li>
                            <li>
                                <a href="user">Users</a>
                            </li>
                            <li>
                                <a href="event">Events</a>
                            </li>
                            <li>
                                <a href="charity">Charities</a>
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