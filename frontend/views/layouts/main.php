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
            <div class="top-menu"> <!-- BEGIN top bar -->
                <ul class="quick-nav">
                    <li class="branding"><a class="logo icon-pullr"></a></li>
                    <li class="add-campaign"><?= Html::encode($this->title) ?>
                        <? if ($this->context->id == 'campaign'): ?>
                            <a href="app/campaign/add" class="icon icon-add2"></a>
                        <? endif ?>
                    </li>
                </ul>
                <?= $this->render('@common/views/leftmenu/avatar'); ?>
                <div class="clearfix"></div>
            </div> <!-- END top bar -->

            <div class="site-content">
            <div class="primary-navigation"> <!-- BEGIN main navigation -->
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
                                <i class="icon icon-streamboard"></i>
                                <span class="nav-label">Streamboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="campaign-link" title="Campaigns" href="app/campaign">
                                <i class="icon icon-bargraph"></i>
                                <span class="nav-label">Campaigns</span>
                            </a>
                        </li>
                        <li>
                            <a class="donor-link" title="Donors" href="app/donor">
                                <i class="icon icon-usergroup"></i>
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

                <a class="primary-nav-toggle"><i class="icon icon-arrowleft3"></i></a>
            </div> <!-- END main navigation -->
            <?= $content ?>

            <?php $this->endBody() ?>
</div>
        </div> <!-- /main-wrapper -->
    </body>
</html>
<?php $this->endPage() ?>