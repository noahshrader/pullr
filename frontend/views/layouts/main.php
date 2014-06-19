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

        <?php $this->beginBody() ?>
        
        <div class="main-wrapper">
            
            <div class="page-sidebar">

                <div class="primary-navigation">

                    <a class="logo icon-pullr"></a>

<!-- The LOGO - We would really like to have a link back to the dashboard or main page here if possible  -->
                
                    <? if (!Yii::$app->user->isGuest): ?>
                       <?= $this->render('@common/views/leftmenu/avatar'); ?>
                    <? endif; ?>

<!-- I've tried my best to comment out what I don't need - basically, I don't need this referring to any bootstrap navigation at all -->

                    <?php
                    // NavBar::begin([
                    // 'brandLabel' => 'pullr',
                    // 'brandUrl' => ".",
                    // 'options' => [
                    // 'class' => 'sidebar-nav nav-top',
                    // ],
                    // ]);
                    // $menuItems = [
                    // ];


// Can you explain to me what the below does?  Is it saying if the user is a guest, show the signup modal?  This was commented out before so I'm not sure if we are using it or not... basically it gave me another logout option?

                    //if (Yii::$app->user->isGuest) {
                    // $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                    // $loginUrl = (strpos(\Yii::$app->request->url, 'site/login')) > 0 ? \Yii::$app->request->url : '/site/login?return=' . urlencode(\Yii::$app->request->url);
                    // $menuItems[] = ['label' => 'Login', 'url' => [$loginUrl]];
                    // } else {

                    // $logoutUrl = 'app/site/logout?return=' . urlencode(\Yii::$app->request->url);
                    // $logoutLink = '<li><a href="' . $logoutUrl . '" > Logout (';
                    // $logoutLink .= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'logoPhoto']]);
                    // $logoutLink .= Yii::$app->user->identity->name . ')</a></li>';
                    // $menuItems[] = $logoutLink;
                    // }


                    // echo Nav::widget([
                    // 'options' => ['class' => 'test-class'],
                    // 'items' => $menuItems,
                    // ]);
                    // NavBar::end();
                    ?>

                    <nav class="sidebar-nav nav-top">
                        <ul> 
                            <? if (!Yii::$app->user->isGuest): ?>
                                <li><a class="streamboard icon-statistics" title="Streamboard" href="dashboard.html" target="_blank">Dashboard</a></li>
                                <li>
                                    <a class="campaign-link icon-heart2" title="Alerts" href="app/campaign"><span>Campaigns</span></a>
                                </li>

    <!-- Can you explain the campaignInvitesCount?  I just wnat to make sure I understand what is being shown here -->

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
                                    <a class="streamboard icon-cog" role="menuitem" tabindex="-1" href="app/settings">Settings</a>
                                </li>
                                   
                            <? endif; ?>
                         </ul>
                    </nav>

                </div><!-- /primary-navigation -->

            </div><!-- /page-sidebar -->

            <div class="page-content-wrap">

                <div class="page-content">

                    <?= Alert::widget() ?>
                    <?= $content ?>

                    <? if (Yii::$app->user->isGuest): ?>

                    <?= $this->render('@frontend/views/site/signupModal'); ?>  

                    <?= $this->render('@frontend/views/site/loginModal'); ?>  

                    <? endif; ?>

                </div>

            </div>

            <?php $this->endBody() ?>

        </div><!-- /main-wrapper -->

    </body>
</html>
<?php $this->endPage() ?>