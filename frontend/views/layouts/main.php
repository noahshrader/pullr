<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\FrontendAsset;
use frontend\widgets\Alert;
use common\widgets\user\UserPhoto;
use common\assets\CommonAsset;
use common\models\CampaignInvite;

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
            
            <header class="page-sidebar">
                <? if (!Yii::$app->user->isGuest): ?>
                   <?= $this->render('@common/views/leftmenu/avatar'); ?>
                <? endif; ?>

                <nav class="navigation">
                    <ul> 
                        <? if (!Yii::$app->user->isGuest): ?>
                            <li>
                                <a class="campaign-link icon-heart2" title="Alerts" href="app/campaign"><span>Campaigns</span></a>
                            </li>
                        <? $campaignInvitesCount = CampaignInvite::find()->where(['userId' => \Yii::$app->user->id, 'status' => CampaignInvite::STATUS_PENDIND])->count(); ?>
                        <? if ($campaignInvitesCount > 0): ?>
                            <li>
                                <a class="campaign-invites icon-text" href="app/campaigninvite"><span>Invites (<?= $campaignInvitesCount?>)</span></a>
                            </li>
                        <? endif; ?>
                            <li>
                                <a class="streamboard-link icon-popup" title="Streamboard" href="streamboard/index.html" target="_blank"><span>Streamboard</span></a>
                            </li>
                            <li>
                                <a class="donations-link icon-heart2" title="Dashboard" href="app/dashboard"><span>Donations</span></a>
                            </li>
                               
                        <? endif; ?>
                     </ul>

                </nav>

                <div class="header-utility">
                    <a class="logo icon-pullr"></a>
                </div>
                
                </header>

                <? if (!Yii::$app->user->isGuest): ?>
                    <a id="add-campaign" class="add-btn openone icon-plus2" href="app/campaign/add"></a>
                <? endif; ?>

                <?= Alert::widget() ?>
                <?= $content ?>

                <? if (Yii::$app->user->isGuest): ?>

                <?= $this->render('@frontend/views/site/signupModal'); ?>  

                <?= $this->render('@frontend/views/site/loginModal'); ?>  

                <? endif; ?>


<?php $this->endBody() ?>
    
    </div>

    </body>
</html>
<?php $this->endPage() ?>
