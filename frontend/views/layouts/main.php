<?php
use yii\helpers\Html;
use common\assets\FrontendAsset;
use common\assets\CommonAsset;
use yii\helpers\Url;
use frontend\models\streamboard\StreamboardConfig;
use common\models\Campaign;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);

$streamboardConfig = StreamboardConfig::get();
if(isset($streamboardConfig))
{
    $js = 'Pullr.baseUrl = "'.Url::to('app').'";';
    $js .= 'Pullr.Streamboard = '.json_encode($streamboardConfig->toArray(['streamboardWidth', 'streamboardHeight', 'streamboardLeft', 'streamboardTop'])).';';
    $js .= 'Pullr.CAMPAIGN_TYPE_CHARITY_FUNDRAISER = "' . Campaign::TYPE_CHARITY_FUNDRAISER . '";';
    $this->registerJs($js);
}

$colorTheme = '';
$user = Yii::$app->user->identity;
if (isset($user))
{
    $user->setScenario('settings');
    $colorTheme = $user->colorTheme == ""?"light":$user->colorTheme;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    <!-- BEGIN body -->
    <body class="<?php echo $colorTheme; ?>">
        <?php $this->beginBody() ?>

        <div class="main-wrapper large-menu-toggled">
            <? if ($this->context->id == 'campaigns') { ?>
            <div class="top-menu indent">
            <? } elseif ($this->context->id == 'donors') { ?>
            <div class="top-menu indent">
            <? } else { ?>
            <div class="top-menu"> <!-- BEGIN top bar -->
            <? } ?>
                <ul class="quick-nav">
                    <li class="add-campaign">
                        <span><?= Html::encode($this->title) ?></span>
                        <? if ($this->context->id == 'campaigns'): ?>
                            <? if(\Yii::$app->user->identity->canCreateMoreCampaigns()): ?>
                                <a href="app/campaigns/add" class="btn-add mdi-content-add" title="Add Campaign"></a>
                            <? else:?>
                                <a href="#" onclick="javascript:return false;" class="btn-add mdi-content-add" data-toggle="tooltip" data-placement="bottom" title="You have reached your active campaigns limit"></a>
                            <? endif;?>
                        <? endif ?>
                    </li>
                </ul>
                <?= $this->render('@common/views/leftmenu/avatar'); ?>
                <div class="clearfix"></div>
            </div> <!-- END top bar -->

            <div class="site-content">
            <div class="primary-navigation"> <!-- BEGIN main navigation -->
                <div class="branding">
                    <a class="logo mdib-pullr-logo"></a>
                </div>
                <nav class="sidebar-nav nav-top">
                    <ul>
                        <li>
                            <a class="dashboard <?if(Yii::$app->controller->id == 'site'):?>active<?endif;?>" title="Dashboard" href="app">
                                <i class="icon mdi-action-dashboard"></i>
                                <span class="nav-label">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="campaign-link <?if(Yii::$app->controller->id == 'campaigns'):?>active<?endif;?>" title="Campaigns" href="app/campaigns">
                                <i class="icon mdi-av-equalizer"></i>
                                <span class="nav-label">Campaigns</span>
                            </a>
                        </li>
                        <li>
                            <a class="streamboard" title="Streamboard" href="app/streamboard" target="_blank">
                                <i class="icon mdi-av-web"></i>
                                <span class="nav-label">Streamboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="donor-link <?if(Yii::$app->controller->id == 'donors'):?>active<?endif;?>" title="Donors" href="app/donors">
                                <i class="icon mdi-social-person"></i>
                                <span class="nav-label">Donors</span>
                            </a>
                        </li>
                        <li>
                            <a class="settings <?if(Yii::$app->controller->id == 'settings'):?>active<?endif;?>" role="menuitem" tabindex="-1" href="app/settings">
                                <i class="icon mdi-action-settings"></i>
                                <span class="nav-label">Settings</span>
                            </a>
                        </li>
                     </ul>
                </nav>
                <a class="primary-nav-toggle icon mdi-navigation-chevron-left"></a>
            </div> <!-- END main navigation -->
            <?= $content ?>

            <?php $this->endBody() ?>
            </div>
        </div> <!-- /main-wrapper -->
    </body>
</html>
<?php $this->endPage() ?>