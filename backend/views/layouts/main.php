<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use common\assets\BackendAsset;
use common\components\Application;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 * @var string $content
 */

CommonAsset::register($this);
BackendAsset::register($this);

$js = 'Pullr.baseUrl = "'.Url::to('@web').'";';
$js .= 'Pullr.setCurrentMenuActiveBackend();';
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
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="main-wrapper">

            <div class="top-menu"> <!-- BEGIN top bar -->
                <ul class="quick-nav">
                    <li class="branding"><a class="logo icon-pullr"></a></li>
                    <li class="add-campaign"><?= Html::encode($this->title) ?>
                        <? if ($this->context->id == 'charity'): ?>
                            <a href="charity/add" class="icon icon-add2"></a>
                        <? endif ?>
                    </li>
                </ul>
                <? if (Application::IsAdmin()): ?>
                    <? if (!Yii::$app->user->isGuest): ?>
                        <?= $this->render('@common/views/leftmenu/avatar'); ?>
                    <? endif; ?>
                <? endif; ?>
                <div class="clearfix"></div>
            </div> <!-- END top bar -->


            <div class="site-content">
                <div class="primary-navigation"> <!-- BEGIN main navigation -->
                    <nav class="sidebar-nav nav-top">       
                        <? if (Application::IsAdmin()): ?>
                            <ul> 

                                    <li>
                                        <a class="reports icon-bargraph" href="">Reports</a>
                                    </li>
                                    <li>
                                        <a class="users icon-usergroup" href="user">Users</a>
                                    </li>
                                    <li>
                                        <a class="campaigns icon-campaigns" href="campaign">Campaigns</a>
                                    </li>
                                    <li>
                                        <a class="charities icon-heart" href="charity">Charities</a>
                                    </li>
                                    <li>
                                        <a class="themes icon-template" href="theme">Themes</a>
                                    </li>
                                    <li>
                                        <a class="themes icon-notification" href="notification">Notifications</a>
                                    </li>
                            </ul>
                        <? endif; ?>
                    </nav>
                </div><!-- END of main navigation -->

                <?= $content ?>

                <?php $this->endBody() ?>

            </div><!-- END of site-content -->
        </div><!-- END of main-wrapper -->
    </body>
</html>
<?php $this->endPage() ?>