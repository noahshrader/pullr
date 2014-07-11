<?php
use yii\helpers\Html;
use common\assets\FrontendAsset;
use common\assets\CommonAsset;
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
    <?= $this->render('baseHead') ?>
    <body>

        <?php $this->beginBody() ?>
        
        <div class="main-wrapper">

            <div class="top-menu">
                <ul class="quick-nav">
                    <li class="branding"><a class="logo icon-pullr"></a></li>
                    <li class="add-campaign"><?= Html::encode($this->title) ?>
                        <? if ($this->context->id == 'campaign'): ?>
                            <a href="app/campaign/add"><i class="icon icon-add"></i></a>
                        <? endif ?>
                    </li>
                </ul>
                <?= $this->render('@common/views/leftmenu/avatar'); ?>

                <div class="clearfix"></div>

            </div>

            <div class="site-content">

                <div class="primary-navigation">

                    <nav class="sidebar-nav nav-top">
                        <ul> 
                                <li><a class="dashboard icon-dashboard" title="Dashboard" href="app">Dashboard</a></li>
                                <li>
                                    <a class="campaign-link icon-campaigns" title="Campaigns" href="app/campaign"><span>Campaigns</span></a>
                                </li>
                                <li>
                                    <a class="streamboard icon-streamboard" title="Streamboard" href="app/streamboard" target="_blank"><span>Streamboard</span></a>
                                </li>
                                <li>
                                    <a class="icon-settings" role="menuitem" tabindex="-1" href="app/settings">Settings</a>
                                </li>
                         </ul>
                    </nav>

                </div><!-- /primary-navigation -->

                <?= $content ?>

                <?php $this->endBody() ?>

            </div><!--App Content-->

        </div><!-- /main-wrapper -->

    </body>
</html>
<?php $this->endPage() ?>