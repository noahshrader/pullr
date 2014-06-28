<?php
use yii\helpers\Html;
use frontend\assets\FrontendAsset;
use common\widgets\user\UserPhoto;
use common\assets\CommonAsset;
use common\models\CampaignInvite;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);

$js = 'Pullr.baseUrl = "'.Url::to('app').'";';
$js .= 'Pullr.setCurrentMenuActive();';
$this->registerJs($js);
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

    <!-- Load Typekit Fonts -->
    <script type="text/javascript" src="//use.typekit.net/qke3nuw.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

    </head>
    <body>

        <?php $this->beginBody() ?>
        
        <div class="main-wrapper">

            <!-- If the user is logged in and is a user -->

            <? if (!Yii::$app->user->isGuest): ?>

            <div class="top-menu">
                
                <ul class="quick-nav">
                    <li class="branding"><a class="logo icon-pullr"></a></li>
                    <li class="add-campaign"><a href="app/campaign/add">Campaigns<i class="icon icon-add"></i></a></li>
                </ul>

                
                <?= $this->render('@common/views/leftmenu/avatar'); ?>

                <div class="clearfix"></div>

            </div>

            <div class="site-content">

                <div class="primary-navigation">

                    <nav class="sidebar-nav nav-top">
                        <ul> 
                            <? if (!Yii::$app->user->isGuest): ?>
                                <li><a class="dashboard icon-dashboard" title="Dashboard" href="app">Dashboard</a></li>
                                <li>
                                    <a class="campaign-link icon-campaigns" title="Campaigns" href="app/campaign"><span>Campaigns</span></a>
                                </li>

                            <? $campaignInvitesCount = CampaignInvite::find()->where(['userId' => \Yii::$app->user->id, 'status' => CampaignInvite::STATUS_PENDIND])->count(); ?>
                            <? if ($campaignInvitesCount > 0): ?>
                                <li>
                                    <a class="campaign-invites icon-text" href="app/campaigninvite"><span>Invites (<?= $campaignInvitesCount?>)</span></a>
                                </li>
                            <? endif; ?>
                                <li>
                                    <a class="streamboard-link icon-streamboard" title="Streamboard" href="/app/streamboard" target="_blank"><span>Streamboard</span></a>
                                </li>

                                <li>
                                    <a class="streamboard icon-settings" role="menuitem" tabindex="-1" href="app/settings">Settings</a>
                                </li>
                                   
                            <? endif; ?>
                         </ul>
                    </nav>

                </div><!-- /primary-navigation -->

                <? endif; ?>

                <? if (Yii::$app->user->isGuest): ?>

                <div class="intro-content-wrapper">

                    <div class="intro-content">

                        <h1><a class="login-logo icon-pullr"></a></h1>

                        <?= $this->render('@frontend/views/site/signupModal'); ?>  

                        <?= $this->render('@frontend/views/site/loginModal'); ?> 

                    </div>

                </div>

                <? endif; ?>

                <?= $content ?>

                <?php $this->endBody() ?>

            </div><!--App Content-->

        </div><!-- /main-wrapper -->

    </body>
</html>
<?php $this->endPage() ?>