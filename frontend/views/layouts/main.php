<?php
use yii\helpers\Html;
use common\assets\FrontendAsset;
use common\assets\CommonAsset;
use yii\helpers\Url;
use frontend\models\streamboard\StreamboardConfig;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);

$streamboardConfig = StreamboardConfig::get();
$js = 'Pullr.baseUrl = "'.Url::to('app').'";';
$js .= 'Pullr.setCurrentMenuActive();';
$js .= 'Pullr.Streamboard = '.json_encode($streamboardConfig->toArray(['streamboardWidth', 'streamboardHeight', 'streamboardLeft', 'streamboardTop'])).';';
$this->registerJs($js);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    <!-- BEGIN body -->
    <body>
        <?php $this->beginBody() ?>
        
        <div class="main-wrapper large-menu-toggled">
            <? if ($this->context->id == 'campaign') { ?>
            <div class="top-menu indent">
            <? } elseif ($this->context->id == 'donor') { ?>
            <div class="top-menu indent">
            <? } else { ?>
            <div class="top-menu"> <!-- BEGIN top bar -->
            <? } ?>
                <ul class="quick-nav">
                    <li class="add-campaign">
                        <span><?= Html::encode($this->title) ?></span>
                        <? if ($this->context->id == 'campaign'): ?>
                            <a href="app/campaign/add" class="icon icon-plus"></a>
                        <? endif ?>
                    </li>
                </ul>
                <?= $this->render('@common/views/leftmenu/avatar'); ?>
                <div class="clearfix"></div>
            </div> <!-- END top bar -->

            <div class="site-content">
            <div class="primary-navigation"> <!-- BEGIN main navigation -->
                <div class="branding"><a class="logo icon-pullr-logo"></a></div>
                <nav class="sidebar-nav nav-top">
                    <ul> 
                        <li>
                            <a class="dashboard" title="Dashboard" href="app">
                                <i class="icon icon-dashboard"></i>
                                <span class="nav-label">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="streamboard" title="Streamboard" href="app/streamboard" target="_blank">
                                <i class="icon icon-board2"></i>
                                <span class="nav-label">Streamboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="campaign-link" title="Campaigns" href="app/campaign">
                                <i class="icon icon-piechart2"></i>
                                <span class="nav-label">Campaigns</span>
                            </a>
                        </li>
                        <li>
                            <a class="donor-link" title="Donors" href="app/donor">
                                <i class="icon icon-user"></i>
                                <span class="nav-label">Donors</span>
                            </a>
                        </li>
                        <li>
                            <a class="settings" role="menuitem" tabindex="-1" href="app/settings">
                                <i class="icon icon-settings"></i>
                                <span class="nav-label">Settings</span>
                            </a>
                        </li>
                     </ul>
                </nav>

                <a class="primary-nav-toggle"><i class="icon icon-arrow-left2"></i></a>
            </div> <!-- END main navigation -->
            <?= $content ?>

            <?php $this->endBody() ?>
</div>
        </div> <!-- /main-wrapper -->
    </body>
    <link href="/js/lib/magnificpopup.css" rel="stylesheet">
    <script src="/js/lib/magnificpopup.js"></script>
    <script>
        $('#go-pro-btn').magnificPopup({
            items:{
                src: '/app/settings/gopro'
            },
            type: 'iframe',
            removalDelay: 300,
            mainClass: 'mfp-fade'
        });

        $('a.deactivate').magnificPopup({
            items:{
                src: '/app/settings/cancelpro'
            },
            type: 'iframe',
            removalDelay: 300,
            mainClass: 'mfp-fade'
        });
    </script>
</html>
<?php $this->endPage() ?>