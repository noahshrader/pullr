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

        <!-- Typekit Font Embed -->
        <script type="text/javascript" src="//use.typekit.net/qke3nuw.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    </head>
    <body>

    <?php $this->beginBody() ?>
        <!-- BEGIN Main Sidebar -->
            <div class="page-sidebar">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="avatar">
                        <?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?>
                    </div>
                <? endif; ?>
                <?php
                NavBar::begin([
                    'brandLabel' => 'pullr',
                    'brandUrl' => "/",
                ]);
                $menuItems = [];
                if (Yii::$app->user->isGuest) {
                } else {
                    $logoutUrl = 'site/logout?return=' . urlencode(\Yii::$app->request->url);
                    $logoutLink = '<li><a href="' . $logoutUrl . '" > Logout (';
                    $logoutLink .= Yii::$app->user->identity->name . ')</a></li>';
                    $menuItems[] = $logoutLink;
                }
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                ]);
                NavBar::end();
                ?>
                <ul> 
                    <? if (Yii::$app->user->isGuest): ?>
                    <li>
                         <?= $this->render('signupModal'); ?>  
                    </li>
                    <li>
                         <?= $this->render('loginModal'); ?>  
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
                            <a href="settings">Settings</a>
                        </li>
                           
                    <? endif; ?>
                </ul>
            </div>
            <!-- END Main Sidebar -->

        <div class="page-wrapper">
            <div class="page-container">
            <?= Alert::widget() ?>
            <?= $content ?>
            </div>
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>