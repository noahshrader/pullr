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

    <!-- Google Webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,400italic,700' rel='stylesheet' type='text/css'>

    </head>
    <body>

        <?php $this->beginBody() ?>
        
        <div class="main-wrapper">

            <!-- If the user is logged in and is a user -->

            <? if (!Yii::$app->user->isGuest): ?>

            <a class="add-campaign" href="app/campaign/add"><i class="icon icon-add"></i></a>
            
            <div class="page-sidebar">

                <div class="primary-navigation">

                    <a class="logo icon-pullr"></a>

                       <a class="avatar avatar-container" href='app'><?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?></a>

                    <nav class="sidebar-nav nav-top">
                        <ul> 
                            <? if (!Yii::$app->user->isGuest): ?>
                                <li><a class="streamboard icon-statistics" title="Streamboard" href="app">Dashboard</a></li>
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
                                    <a class="streamboard-link icon-popup" title="Streamboard" href="/app/streamboard" target="_blank"><span>Streamboard</span></a>
                                </li>

                                <li>
                                    <a class="streamboard icon-cog" role="menuitem" tabindex="-1" href="app/settings">Settings</a>
                                </li>
                                   
                            <? endif; ?>
                         </ul>
                    </nav>

                    <nav class="sidebar-nav nav-bottom">
                        <ul>
                            <li role="presentation"><a class="streamboard icon-liferaft" role="menuitem" href="app/help">Help</a></li>
                            <li role="presentation"><a role="menuitem" href="app/site/logout">Logout</a></li>
                        </ul>
                    </nav>

                </div><!-- /primary-navigation -->

            </div><!-- /page-sidebar -->

            <? endif; ?>

            <div class="page-content-wrap">

                <div class="page-content">

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

                </div>

            </div>

            <?php $this->endBody() ?>

        </div><!-- /main-wrapper -->

    </body>
</html>
<?php $this->endPage() ?>